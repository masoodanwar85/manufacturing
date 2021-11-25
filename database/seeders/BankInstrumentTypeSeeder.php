<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankInstrumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryBankInstrumentTypes = ['Cheque'];
        foreach ($aryBankInstrumentTypes as $bankInstrumentType) {
            DB::table('bankInstrumentType')->insert([
				'bankInstrumentType' => $bankInstrumentType
			]);
        }
    }
}
