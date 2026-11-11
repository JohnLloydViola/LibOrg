<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default Administrator account
        Member::firstOrCreate(
            ['email' => 'admin@liborg.com'],
            [
                'full_name' => 'System Administrator',
                'password_hash' => Hash::make('admin12345'),
                'phone_number' => '09123456789',
                'address' => 'Tarlac State University, San Isidro Campus',
                'role' => 'admin',
                'api_token' => bin2hex(random_bytes(40)),
            ]
        );

        // Create default Regular Member account
        Member::firstOrCreate(
            ['email' => 'WMA@liborg.com'],
            [
                'full_name' => 'WMA 4',
                'password_hash' => Hash::make('password123'),
                'phone_number' => '09987654321',
                'address' => 'Tarlac City, Tarlac',
                'role' => 'member',
                'api_token' => bin2hex(random_bytes(40)),
            ]
        );
    }
}
