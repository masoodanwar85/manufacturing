<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryBanks = [
            ['bankName' => 'Al Baraka Bank (Pakistan) Limited'],
			['bankName' => 'Allied Bank Limited'],
			['bankName' => 'Askari Bank Limited'],
			['bankName' => 'Bank Alfalah Limited'],
			['bankName' => 'Bank Al-Habib Limited'],
			['bankName' => 'BankIslami Pakistan Limited'],
			['bankName' => 'Dubai Islamic Bank Pakistan Limited'],
			['bankName' => 'Faysal Bank Limited'],
			['bankName' => 'First Women Bank Limited'],
			['bankName' => 'Habib Bank Limited'],
			['bankName' => 'Standard Chartered Bank (Pakistan) Limited'],
			['bankName' => 'Habib Metropolitan Bank Limited'],
			['bankName' => 'Industrial and Commercial Bank of China'],
			['bankName' => 'Industrial Development Bank of Pakistan'],
			['bankName' => 'JS Bank Limited'],
			['bankName' => 'MCB Bank Limited'],
			['bankName' => 'Meezan Bank Limited'],
			['bankName' => 'National Bank of Pakistan'],
			['bankName' => 'Samba Bank Limited'],
			['bankName' => 'Silk Bank Limited'],
			['bankName' => 'Sindh Bank Limited'],
			['bankName' => 'Soneri Bank Limited'],
			['bankName' => 'Summit Bank Limited'],
			['bankName' => 'The Bank of Khyber'],
			['bankName' => 'The Bank of Punjab'],
			['bankName' => 'United Bank Limited'],
			['bankName' => 'Zarai Taraqiati Bank Limited']
        ];
        foreach ($aryBanks as $bank) {
            \Illuminate\Support\Facades\DB::table('bank')->insert([
				'bankName' => $bank['bankName']
			]);
        }
    }
}
