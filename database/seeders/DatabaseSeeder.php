<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 use WithoutModelEvents;
 public function run(): void {
  User::updateOrCreate(['email'=>'admin@gmail.com'],[
   'name'=>'Admin LyyrsBeauty','password'=>Hash::make('Admin12345!'),'role'=>'admin'
  ]);
 }
}
