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
            ['batchName' => 'Batch 1', 'startDate' => '2021-01-01', 'endDate' => '2022-01-01','description' => 'The best batch 1'],
            ['batchName' => 'Batch 2', 'startDate' => '2022-01-02', 'endDate' => '2023-01-01','description' => 'The best batch 2'],
            ['batchName' => 'Batch 3', 'startDate' => '2023-01-02', 'endDate' => '2024-01-01','description' => 'The best batch 3']
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
