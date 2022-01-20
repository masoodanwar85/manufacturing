<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryLeaveTypes = [
            ['leaveType' => 'Absent', 'isPaid' => 0],
            ['leaveType' => 'Earned Leave', 'isPaid' => 1],
			['leaveType' => 'Casual Leave', 'isPaid' => 1],
			['leaveType' => 'Sick Leave', 'isPaid' => 1]
        ];
        foreach ($aryLeaveTypes as $leaveType) {
            \Illuminate\Support\Facades\DB::table('leaveType')->insert([
				'leaveType' => $leaveType['leaveType'],
                'isPaid' => $leaveType['isPaid'],
				'createdByUserID' => 1
			]);
        }
    }
}
