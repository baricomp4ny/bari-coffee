<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun admin untuk login ke /admin. Ganti lewat .env (ADMIN_EMAIL / ADMIN_PASSWORD) sebelum seeding.
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@baricoffee.test')],
            ['name' => 'Admin Bari Coffee', 'password' => bcrypt(env('ADMIN_PASSWORD', 'password'))],
        );
        // is_admin sengaja tidak fillable agar tidak bisa diisi dari form
        $admin->forceFill(['is_admin' => true])->save();

        $this->call(MenuSeeder::class);
    }
}
