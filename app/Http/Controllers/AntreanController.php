<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use RuntimeException;

class AntreanController extends Controller
{
    private const JAM_BUKA = '08:00';
    private const JAM_TUTUP = '21:30';

    private const KUOTA_LAYANAN = [
        'hair coloring' => 8,
        'hair styling' => 10,
        'creambath' => 12,
    ];

    /**
     * Mengambil kuota berdasarkan nama layanan.
     */
    private function getKuota(Layanan $layanan): int
    {
        $nama = strtolower(trim($layanan->nama_layanan));

        return self::KUOTA_LAYANAN[$nama] ?? 0;
    }

    /**
     * Mengambil durasi layanan dalam menit.
     */
    private function getDurasi(Layanan $layanan): int
    {
        $durasi = $layanan->durasi ?? 0;

        if (is_numeric($durasi)) {
            return (int) $durasi;
        }

        preg_match('/\d+/', (string) $durasi, $matches);

        return isset($matches[0]) ? (int) $matches[0] : 0;
    }

    /**
     * Mengambil antrean aktif pada tanggal tertentu.
     * Antrean batal dan pembayaran ditolak tidak dihitung.
     */
    private function antreanAktif($tanggal)
    {
        return Antrean::with('layanan')
            ->whereDate('tanggal_antrean', $tanggal)
            ->where('status', '!=', 'batal')
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhere('payment_status', '!=', 'ditolak');
            })
            ->orderBy('created_at')
            ->orderBy('id');
    }

    /**
     * Menghitung estimasi jam pelayanan.
     */
    private function hitungEstimasi($antreans): void
    {
        foreach ($antreans->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_antrean)->toDateString();
        }) as $tanggal => $items) {

            $jamBerjalan = Carbon::parse(
                $tanggal . ' ' . self::JAM_BUKA
            );

            foreach ($items as $antrean) {
                if (!$antrean->layanan) {
                    $antrean->perkiraan_jam = null;
                    continue;
                }

                $antrean->perkiraan_jam = $jamBerjalan->format('H:i');

                $jamBerjalan->addMinutes(
                    $this->getDurasi($antrean->layanan)
                );
            }
        }
    }

    /**
     * Memeriksa kuota dan jam pelayanan.
     */
    private function periksaKetersediaan(
        Layanan $layanan,
        string $tanggal,
        ?int $abaikanId = null
    ): void {

        $durasi = $this->getDurasi($layanan);
        $kuotaMaksimal = $this->getKuota($layanan);

        if ($durasi <= 0 || $kuotaMaksimal <= 0) {
            throw new RuntimeException(
                'Durasi atau kuota layanan belum dikonfigurasi.'
            );
        }

        $sekarang = now();

        if (
            $sekarang->format('H:i') < self::JAM_BUKA ||
            $sekarang->format('H:i') >= self::JAM_TUTUP
        ) {
            throw new RuntimeException(
                'Salon hanya menerima booking pukul 08.00 sampai 21.30.'
            );
        }

        $antreanHariIni = $this->antreanAktif($tanggal)->get();

        if ($abaikanId !== null) {
            $antreanHariIni = $antreanHariIni
                ->reject(fn ($item) => $item->id === $abaikanId)
                ->values();
        }

        // Periksa kuota layanan.
        $jumlahLayanan = $antreanHariIni
            ->where('layanan_id', $layanan->id)
            ->count();

        if ($jumlahLayanan >= $kuotaMaksimal) {
            throw new RuntimeException(
                "Kuota {$layanan->nama_layanan} hari ini sudah penuh. Maksimal {$kuotaMaksimal} antrean."
            );
        }

        // Hitung akumulasi durasi antrean sebelumnya.
        $jamMulai = Carbon::parse(
            $tanggal . ' ' . self::JAM_BUKA
        );

        foreach ($antreanHariIni as $item) {
            if ($item->layanan) {
                $jamMulai->addMinutes(
                    $this->getDurasi($item->layanan)
                );
            }
        }

        // Booking baru tidak boleh mendapatkan estimasi waktu yang sudah lewat.
        if ($jamMulai->lessThan($sekarang)) {
            $jamMulai = $sekarang->copy();
        }

        $jamSelesai = $jamMulai->copy()->addMinutes($durasi);

        $batasTutup = Carbon::parse(
            $tanggal . ' ' . self::JAM_TUTUP
        );

        if (
            $jamMulai->greaterThanOrEqualTo($batasTutup) ||
            $jamSelesai->greaterThan($batasTutup)
        ) {
            throw new RuntimeException(
                'Booking tidak dapat dilakukan karena perkiraan waktu pelayanan melewati jam tutup salon pukul 21.30.'
            );
        }
    }

    /**
     * Menampilkan daftar antrean.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $builder = Antrean::with('layanan')
            ->when(
                auth()->user()->role !== 'admin',
                fn ($b) => $b->where('user_id', auth()->id())
            )
            ->when($query !== '', function ($b) use ($query) {
                $b->where(function ($w) use ($query) {
                    $w->where('nomor_antrean', 'like', "%$query%")
                        ->orWhere('no_hp', 'like', "%$query%")
                        ->orWhere('nama_pelanggan', 'like', "%$query%");
                });
            });

        $antreans = $builder->latest()->get();

        // Hitung estimasi berdasarkan semua antrean aktif setiap tanggal.
        foreach (
            $antreans->groupBy(
                fn ($item) => Carbon::parse($item->tanggal_antrean)->toDateString()
            ) as $tanggal => $items
        ) {
            $semuaAntrean = $this->antreanAktif($tanggal)->get();

            $this->hitungEstimasi($semuaAntrean);

            foreach ($items as $item) {
                $hasil = $semuaAntrean->firstWhere('id', $item->id);

                $item->perkiraan_jam = $hasil?->perkiraan_jam;
            }
        }

        return view('antrean.index', compact('antreans', 'query'));
    }

    /**
     * Form pendaftaran antrean.
     */
    public function create()
    {
        $tanggal = now()->toDateString();

        $layanans = Layanan::orderBy('nama_layanan')->get();

        $kuotaTerpakai = Antrean::whereDate('tanggal_antrean', $tanggal)
            ->where('status', '!=', 'batal')
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhere('payment_status', '!=', 'ditolak');
            })
            ->select('layanan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('layanan_id')
            ->pluck('total', 'layanan_id');

        return view('antrean.create', compact(
            'layanans',
            'kuotaTerpakai'
        ));
    }

    /**
     * Menyimpan booking antrean.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'layanan_id' => 'required|exists:layanans,id',
        ]);

        $layanan = Layanan::findOrFail($data['layanan_id']);

        $tanggal = now()->toDateString();

        try {
            $antrean = DB::transaction(function () use (
                $data,
                $layanan,
                $tanggal
            ) {

                /*
                 * Mengunci data layanan agar proses booking bersamaan
                 * tidak mudah menyebabkan kuota terlampaui.
                 */
                Layanan::orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $this->periksaKetersediaan(
                    $layanan,
                    $tanggal
                );

                return Antrean::create($data + [
                    'user_id' => auth()->id(),
                    'total_pembayaran' => $layanan->harga,
                    'nomor_antrean' => null,
                    'tanggal_antrean' => $tanggal,
                    'status' => 'menunggu',
                    'payment_status' => 'menunggu_pembayaran',
                ]);
            });

        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'layanan_id' => $e->getMessage(),
                ]);
        }

        return redirect()->route('antrean.payment', $antrean)
            ->with(
                'success',
                'Booking berhasil dibuat. Silakan bayar sesuai nominal melalui QRIS.'
            );
    }

    /**
     * Halaman pembayaran.
     */
    public function payment(Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        $antrean->load('layanan');

        if ($antrean->payment_status === 'terverifikasi') {
            return redirect()->route('antrean.show', $antrean)
                ->with('success', 'Pembayaran sudah diverifikasi.');
        }

        return view('antrean.payment', compact('antrean'));
    }

    /**
     * Upload bukti pembayaran.
     */
    public function uploadPaymentProof(Request $request, Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        abort_if(
            $antrean->payment_status === 'terverifikasi',
            403,
            'Pembayaran sudah diverifikasi.'
        );

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $directory = public_path('storage/bukti-pembayaran');

        File::ensureDirectoryExists($directory);

        if ($antrean->payment_proof) {
            $oldPath = public_path('storage/' . $antrean->payment_proof);

            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('payment_proof');

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        $antrean->update([
            'payment_proof' => 'bukti-pembayaran/' . $filename,
            'payment_status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('antrean.payment', $antrean)
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim. Tunggu verifikasi admin.'
            );
    }

    /**
     * Detail antrean.
     */
    public function show(Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        $antrean->load('layanan');

        if ($antrean->payment_status !== 'terverifikasi') {
            return redirect()->route('antrean.payment', $antrean);
        }

        $semuaAntrean = $this->antreanAktif(
            $antrean->tanggal_antrean
        )->get();

        $this->hitungEstimasi($semuaAntrean);

        $hasil = $semuaAntrean->firstWhere('id', $antrean->id);

        $antrean->perkiraan_jam = $hasil?->perkiraan_jam;

        return view('antrean.show', compact('antrean'));
    }

    /**
     * Edit booking.
     */
    public function edit(Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        abort_if($antrean->payment_status === 'terverifikasi', 403);
        abort_if($antrean->status === 'selesai', 403);
        abort_if($antrean->status === 'batal', 403);

        return view('antrean.edit', [
            'antrean' => $antrean,
            'layanans' => Layanan::orderBy('nama_layanan')->get(),
        ]);
    }

    /**
     * Update booking.
     */
    public function update(Request $request, Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        abort_if($antrean->payment_status === 'terverifikasi', 403);
        abort_if($antrean->status === 'selesai', 403);
        abort_if($antrean->status === 'batal', 403);

        $data = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'layanan_id' => 'required|exists:layanans,id',
        ]);

        $layanan = Layanan::findOrFail($data['layanan_id']);

        try {
            DB::transaction(function () use (
                $data,
                $layanan,
                $antrean
            ) {

                Layanan::orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $this->periksaKetersediaan(
                    $layanan,
                    $antrean->tanggal_antrean->toDateString(),
                    $antrean->id
                );

                $antrean->update($data + [
                    'total_pembayaran' => $layanan->harga,
                    'payment_status' => 'menunggu_pembayaran',
                    'payment_proof' => null,
                ]);
            });

        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'layanan_id' => $e->getMessage(),
                ]);
        }

        return redirect()->route('antrean.payment', $antrean)
            ->with(
                'success',
                'Booking diperbarui. Silakan lakukan pembayaran.'
            );
    }

    /**
     * Membatalkan booking.
     */
    public function cancel(Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        abort_if(auth()->user()->role === 'admin', 403);

        abort_if($antrean->status === 'batal', 422);

        abort_if(
            in_array($antrean->status, ['dilayani', 'selesai'], true),
            422
        );

        abort_if(
            $antrean->payment_status === 'terverifikasi',
            422,
            'Pembayaran sudah diverifikasi. Hubungi admin salon.'
        );

        abort_if(
            $antrean->payment_status === 'menunggu_verifikasi',
            422,
            'Bukti pembayaran sedang diperiksa.'
        );

        $antrean->update([
            'status' => 'batal',
        ]);

        return redirect()->route('antrean.index')
            ->with(
                'success',
                'Booking berhasil dibatalkan. Kuota dan estimasi antrean akan diperbarui.'
            );
    }

    /**
     * Menghapus booking.
     */
    public function destroy(Antrean $antrean)
    {
        $this->authorizeAntrean($antrean);

        abort_if($antrean->payment_status === 'terverifikasi', 403);
        abort_if($antrean->status === 'selesai', 403);

        if ($antrean->payment_proof) {
            $oldPath = public_path('storage/' . $antrean->payment_proof);

            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $antrean->delete();

        return redirect()->route('antrean.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Memeriksa hak akses antrean.
     */
    private function authorizeAntrean(Antrean $antrean): void
    {
        abort_unless(
            auth()->user()->role === 'admin' ||
            $antrean->user_id === auth()->id(),
            403
        );
    }
}