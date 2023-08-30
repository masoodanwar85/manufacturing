<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AccruedSalaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salaries:accrued';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'I make entries in database for accrued salaries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $staffSalaries = \App\Models\Staff::where('isActive', 1)->where('paymentAmount', '>', '0')->get();
        $addedRecords = 0;
        foreach ($staffSalaries as $staffSalary) {
            for ($ctr = 30; $ctr >= 0; $ctr--) {
                $today = \Carbon\Carbon::now()->subDay($ctr);

                $transaction = \App\Models\Transaction::where('transactionDate', $today->toDateString())
                    ->whereHas('transactionDetails', function ($query) use ($staffSalary) {
                        $query->where('headID', \Config::get('constants.account_heads.salaries_payable'))->where('subHeadID', $staffSalary->headID)->where('isDebit', 0);
                    })->get();

                if (count($transaction)) {
                    continue;
                }

                $isAddAccruedSalary = FALSE;

                $salaryAmount = $staffSalary->paymentAmount;

                switch ($staffSalary->paymentFrequencyID) {
                    case 1:
                        // Monthly
                        $isLastDayOfMonth = $today->toDateString() == $today->endOfMonth()->toDateString();
                        if ($isLastDayOfMonth && $today->hour >= 18) {
                            // Add Salaries Payable in DB for this staff
                            $totalDays = \App\Models\Attendance::where('staffID', $staffSalary->staffID)->whereBetween('attendanceDate', [$today->firstOfMonth()->toDateString(), $today->lastOfMonth()->toDateString()])->whereRaw('leaveTypeID IN (SELECT leaveTypeID FROM leaveType WHERE isPaidToMonthly = 1)')->count();
                            $perDaySalary = $salaryAmount / $today->daysInMonth;
                            $salaryAmount = $perDaySalary * $totalDays;
                            if ($salaryAmount > 0) {
                                $isAddAccruedSalary = TRUE;
                            }
                        }
                        break;
                    case 2:
                        // Daily
                        // Add Salaries Payable in DB for this staff
                        $attendance = \App\Models\Attendance::where('staffID', $staffSalary->staffID)->whereDate('attendanceDate', $today->toDateString())->first();
                        if ($attendance && $attendance->leaveType->isPaidToDaily == 1) {
                            $isAddAccruedSalary = TRUE;
                        }
                        break;
                    default:
                }

                if ($isAddAccruedSalary == TRUE) {
                    $transactionID = \App\Services\TransactionService::addTransactionArray(['isPaymentReceipt' => 1, 'transactionTypeID' => 1, 'transactionDate' => $today->toDateString(), 'createdByUserID' => 1]);
                    \App\Services\TransactionService::addTransactionDetailArray(['transactionID' => $transactionID, 'headID' => \Config::get('constants.account_heads.salaries'), 'subHeadID' => $staffSalary->headID, 'isDebit' => 1, 'amount' => $salaryAmount, 'description' => 'Salary Expense for the staff']);
                    \App\Services\TransactionService::addTransactionDetailArray(['transactionID' => $transactionID, 'headID' => \Config::get('constants.account_heads.salaries_payable'), 'subHeadID' => $staffSalary->headID, 'isDebit' => 0, 'amount' => $salaryAmount, 'description' => 'Accrued/Payable Salary for the staff']);
                    $addedRecords++;
                }
            }

        }
        $this->info($addedRecords);
    }
}