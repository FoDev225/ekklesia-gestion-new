<?php

namespace Database\Seeders;

use App\Models\FinanceAccount;
use Illuminate\Database\Seeder;

class FinanceAccountSeeder extends Seeder
{
    public function run(): void
    {
        FinanceAccount::firstOrCreate(['name' => 'Banque 1'], ['type' => 'banque']);
        FinanceAccount::firstOrCreate(['name' => 'Banque 2'], ['type' => 'banque']);
        FinanceAccount::firstOrCreate(['name' => 'Caisse'], ['type' => 'caisse', 'alert_threshold' => 150000]);
    }
}