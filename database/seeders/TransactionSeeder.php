<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $statuses = ['pending', 'paid', 'expired'];

        foreach ($users as $index => $user) {
            $status = $statuses[$index % count($statuses)];

            Transaction::create([
                'user_id' => $user->id,
                'status' => $status,
                'total_pembayaran' => 120000.00,
                'tipe_pembayaran' => 'Transfer Bank',
                'expired_at' => now()->addDays(1),
            ]);
        }
    }
}
