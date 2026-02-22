<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\FeedbackUser;

class FeedbackUserSeeder extends Seeder
{
    public function run(): void
    {
        FeedbackUser::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'pesan' => 'Aplikasi ini sangat bagus!',
        ]);

        FeedbackUser::create([
            'nama' => 'Jane Smith',
            'email' => 'jane@example.com',
            'pesan' => 'Mohon tambahkan lebih banyak template.',
        ]);
    }
}
