<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $useUsername = Schema::hasColumn('users', 'username');
        $useEmail = Schema::hasColumn('users', 'email');
        $useName = Schema::hasColumn('users', 'name');

        $accounts = [
            ['id' => 'admin', 'password' => 'admin123'],
            ['id' => 'user', 'password' => 'user123'],
        ];

        foreach ($accounts as $acct) {
            $data = [
                'password' => Hash::make($acct['password']),
            ];

            // Add available identifier columns; provide sensible defaults where needed
            if ($useUsername) {
                $data['username'] = $acct['id'];
            }

            if ($useEmail) {
                $data['email'] = $acct['id'] . '@example.com';
            }

            if ($useName) {
                $data['name'] = ucfirst($acct['id']);
            }

            // Determine match attributes (prefer username, then email, then name)
            $match = [];
            if ($useUsername) {
                $match = ['username' => $acct['id']];
            } elseif ($useEmail) {
                $match = ['email' => $acct['id'] . '@example.com'];
            } elseif ($useName) {
                $match = ['name' => ucfirst($acct['id'])];
            }

            if (empty($match)) {
                continue;
            }

            // Upsert: create or update existing user
            User::updateOrCreate($match, $data);
        }
    }
}
