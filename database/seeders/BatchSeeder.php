<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryBatches = [
            ['batchName' => 'Batch 1', 'startDate' => '2017-01-01', 'endDate' => '2018-01-01','description' => 'The best batch 1'],
            ['batchName' => 'Batch 2', 'startDate' => '2018-01-02', 'endDate' => '2019-01-01','description' => 'The best batch 2'],
            ['batchName' => 'Batch 3', 'startDate' => '2019-01-02', 'endDate' => '2020-01-01','description' => 'The best batch 3'],
            ['batchName' => 'Batch 4', 'startDate' => '2020-01-02', 'endDate' => '2021-01-01','description' => 'The current batch 4'],
			['batchName' => 'Batch 5', 'startDate' => '2021-01-02', 'endDate' => '2022-01-01','description' => 'The next batch 5']
        ];
        foreach ($aryBatches as $batch) {
            \Illuminate\Support\Facades\DB::table('batch')->insert([
				'batchName' => $batch['batchName'],
				'startDate' => $batch['startDate'],
				'endDate' => $batch['endDate'],
				'description' => $batch['description'],
				'createdByUserID' => 1
			]);
        }
    }
}
