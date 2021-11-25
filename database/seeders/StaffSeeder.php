<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$aryStaff = [
            ['staffName' => 'Masood Anwar','staffTypeID' => 1, 'dateJoined' => '2020-12-20'],
            ['staffName' => 'Tayyab Hussain','staffTypeID' => 2, 'dateJoined' => '2020-12-20'],
			['staffName' => 'Javaid Khan','staffTypeID' => 3, 'dateJoined' => '2020-12-20'],
			['staffName' => 'Ahmed Khan','staffTypeID' => 4, 'dateJoined' => '2020-12-20'],
			['staffName' => 'Akbar Khan','staffTypeID' => 5, 'dateJoined' => '2020-12-20']
        ];
        foreach ($aryStaff as $staff) {
			$headID = DB::table('accountHead')->insertGetId([
				'parentHeadID' => \Config::get('constants.account_heads.staff'),
                'rootHeadID' => \Config::get('constants.account_heads.staff'),
				'headName' => $staff['staffName'] . '- Employee - ' . ' (Staff)',
				'isSystemGenerated' => 1,
				'isEditable' => 0,
				'isShowForPurchaseOrderExpense' => 0,
				'createdByUserID' => 1
			]);
            DB::table('staff')->insert([
				'headID' => $headID,
				'staffName' => $staff['staffName'],
				'staffTypeID' => $staff['staffTypeID'],
				'dateJoined' => $staff['dateJoined'],
				'createdByUserID' => 1
			]);
        }
    }
}
