<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryTransactionTypes = [
            ['transactionTypeID' => 1, 'transactionType' => 'Payments'],
			['transactionTypeID' => 2, 'transactionType' => 'Receipts']
        ];
        foreach ($aryTransactionTypes as $transactionType) {
            \Illuminate\Support\Facades\DB::table('transactionType')->insert(['transactionTypeID' => $transactionType['transactionTypeID'],'transactionType' => $transactionType['transactionType']]);
        }
    }
}
