<?php

namespace App\Http\Controllers;

use App\Models\Antrean;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $totalAntrean = Antrean::count();
        $totalLayanan = Layanan::count();
        $bookingBatal = Antrean::where('status', 'batal')->count();
        $antreanSelesai = Antrean::where('status', 'selesai')
            ->where('payment_status', 'terverifikasi')
            ->count();
        $totalPendapatan = Antrean::where('status', 'selesai')
            ->where('payment_status', 'terverifikasi')
            ->with('layanan')->get()
            ->sum(fn ($a) => (int) ($a->total_pembayaran ?? $a->layanan?->harga ?? 0));
        $antreans = Antrean::with(['layanan', 'user'])->latest()->take(8)->get();
        $pendingPayments = Antrean::with(['layanan', 'user'])
            ->where('payment_status', 'menunggu_verifikasi')
            ->where('status', '!=', 'batal')
            ->latest()->get();

        return view('admin.dashboard', compact(
            'totalAntrean', 'totalLayanan', 'bookingBatal', 'antreanSelesai', 'totalPendapatan', 'antreans', 'pendingPayments'
        ));
    }

    public function updateStatus(Request $request, Antrean $antrean)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_unless($antrean->payment_status === 'terverifikasi', 403, 'Verifikasi pembayaran sebelum mengubah status antrean.');

        $data = $request->validate([
            'status' => 'required|in:menunggu,dilayani,selesai,batal',
        ]);

        $antrean->update($data);

        return back()->with('success', 'Status antrean berhasil diperbarui.');
    }

    public function verifyPayment(Antrean $antrean)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_if($antrean->status === 'batal', 422, 'Booking sudah dibatalkan pelanggan dan tidak dapat diverifikasi.');

        if ($antrean->payment_status === 'terverifikasi') {
            return back()->with('success', 'Pembayaran ini sudah diverifikasi.');
        }

        abort_unless($antrean->payment_status === 'menunggu_verifikasi' && $antrean->payment_proof, 422, 'Belum ada bukti pembayaran yang dapat diverifikasi.');

        DB::transaction(function () use ($antrean) {
            $antrean->refresh();
            $today = now()->toDateString();
            $next = Antrean::whereDate('tanggal_antrean', $today)
                ->whereNotNull('nomor_antrean')
                ->count() + 1;

            do {
                $number = 'LYR-' . now()->format('Ymd') . '-' . str_pad((string) $next++, 3, '0', STR_PAD_LEFT);
            } while (Antrean::where('nomor_antrean', $number)->exists());

            $antrean->update([
                'nomor_antrean' => $number,
                'tanggal_antrean' => $today,
                'payment_status' => 'terverifikasi',
                'payment_verified_at' => now(),
                'status' => 'menunggu',
            ]);
        });

        return back()->with('success', 'Pembayaran diverifikasi dan nomor antrean berhasil diterbitkan.');
    }

    public function rejectPayment(Request $request, Antrean $antrean)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        abort_if($antrean->status === 'batal', 422, 'Booking sudah dibatalkan pelanggan.');
        abort_unless($antrean->payment_status === 'menunggu_verifikasi', 422, 'Pembayaran ini tidak sedang menunggu verifikasi.');

        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500',
        ]);

        $antrean->update([
            'payment_status' => 'ditolak',
            'payment_verified_at' => null,
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak. Pelanggan dapat mengunggah bukti baru.');
    }
}
