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
            ['leaveType' => 'Present', 'isPaidToMonthly' => 1, 'isPaidToDaily' => 1],
            ['leaveType' => 'Absent', 'isPaidToMonthly' => 0, 'isPaidToDaily' => 0],
            ['leaveType' => 'Earned Leave', 'isPaidToMonthly' => 1, 'isPaidToDaily' => 1],
			['leaveType' => 'Casual Leave', 'isPaidToMonthly' => 1, 'isPaidToDaily' => 0],
			['leaveType' => 'Sick Leave', 'isPaidToMonthly' => 1, 'isPaidToDaily' => 0],
            ['leaveType' => 'Holiday', 'isPaidToMonthly' => 1, 'isPaidToDaily' => 0]
        ];
        foreach ($aryLeaveTypes as $leaveType) {
            \Illuminate\Support\Facades\DB::table('leaveType')->insert([
				'leaveType' => $leaveType['leaveType'],
                'isPaidToMonthly' => $leaveType['isPaidToMonthly'],
                'isPaidToDaily' => $leaveType['isPaidToDaily'],
				'createdByUserID' => 1
			]);
        }
    }
}
