<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankInstrumentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryBankInstrumentStatuses = ['Processing','Cleared','Bounced','Dishonoured'];
        foreach ($aryBankInstrumentStatuses as $bankInstrumentStatus) {
            DB::table('bankInstrumentStatus')->insert([
				'bankInstrumentStatus' => $bankInstrumentStatus
			]);
        }
    }
}
