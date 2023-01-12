<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountHead;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreAccountHeadRequest;
use App\Http\Requests\UpdateAccountHeadRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Arr;
use App\DataTables\AccountHeadDataTable;

class AccountHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('account_head_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if ($request->ajax()) {
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
			$query = DB::table('accountHead')
                        ->leftJoin('accountHead AS parentHead','parentHead.headID','=','accountHead.parentHeadID')
						->where('accountHead.isSystemGenerated', 0)
                        ->select(DB::raw('accountHead.*,parentHead.headName AS parentHeadName'))
                        ->groupBy('accountHead.headID')
						->orderBy('accountHead.headID')
                        ->get();

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'account_head_read';
				$editGate = '';
				$deleteGate = '';
				if ($row->isEditable == 1) {
					$editGate      = 'account_head_update';
	                $deleteGate    = 'account_head_delete';
				}
                $crudRoutePart = 'accountHead';
                $primaryKey = 'headID';
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('head', function ($row) {
                return $row->headName ? $row->headName : "";
            });
            $table->editColumn('parentHeadName', function ($row) {
                return $row->parentHeadName ? $row->parentHeadName : "";
            });
			$table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

			// return $table->render('users.index');
            return $table->make(true);
        }

        return view('admin.accountHead.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('account_head_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accountHeads = AccountHead::where('isSystemGenerated', 0)->orderBy('headName','asc')->get();
		return view('admin.accountHead.create',compact('accountHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountHeadRequest $request)
    {
        DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$request->request->add(['isSystemGenerated' => 0]);
			$request->request->add(['isEditable' => 1]);
            if ($request->parentHeadID == "-1") {
                $request->request->add(['rootHeadID' => NULL]);
            } else {
                $rootHeadID = AccountHead::find($request->parentHeadID)->rootHeadID;
                if ($rootHeadID == NULL) {
                    $request->request->add(['rootHeadID' => $request->parentHeadID]);
                } else {
                    $request->request->add(['rootHeadID' => $rootHeadID]);
                }
            }
			$accountHead = AccountHead::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Account Head created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
            dd($e);
			$request->session()->flash('error', 'An error occurred while creating Account Head!');
		}
		return redirect()->route('accountHead.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function show(AccountHead $accountHead)
    {
		abort_if(Gate::denies('account_head_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.accountHead.show', compact('accountHead'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountHead $accountHead, Request $request)
    {
		abort_if(Gate::denies('account_head_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$accountHeads = AccountHead::where('isSystemGenerated', 0)->get();
        return view('admin.accountHead.edit', compact('accountHead','accountHeads'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountHeadRequest $request, AccountHead $accountHead)
    {
		DB::beginTransaction();
		try {
            if ($request->parentHeadID == "-1") {
                $request->request->add(['rootHeadID' => NULL]);
            } else {
                $rootHeadID = AccountHead::find($request->parentHeadID)->rootHeadID;
                if ($rootHeadID == NULL) {
                    $request->request->add(['rootHeadID' => $request->parentHeadID]);
                } else {
                    $request->request->add(['rootHeadID' => $rootHeadID]);
                }
            }
			$accountHead->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Account Head updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating Account Head!');
		}
		return redirect()->route('accountHead.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountHead $accountHead,Request $request)
    {
		abort_if(Gate::denies('account_head_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if ($accountHead->isEditable == 0) {
			$request->session()->flash('warning', 'You cannot delete this Account Head!');
			return view('admin.accountHead.index');
		}
		DB::beginTransaction();
		try {
			$accountHead->delete();
			DB::commit();
			$request->session()->flash('message', 'Account Head deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting Account Head!');
		}

        return redirect()->route('accountHead.index');
    }

    public function paymentsReceipts(Request $request) {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $filters = array();

        $filters['isIgnoreDates'] = $request->isIgnoreDates;
        $filters['customerID'] = $request->customerID;
        $filters['headID'] = $request->headID;
        $filters['subHeadID'] = $request->subHeadID;

        $filters['fromDate'] = date('Y-m-01');
        $filters['toDate'] = date('Y-m-d');
        $filters['transactionTypeNumber'] = "";
        if (!empty($request->fromDate) && strtotime($request->fromDate)) {
            $filters['fromDate'] = date('Y-m-d', strtotime($request->fromDate));
        }
        if (!empty($request->toDate) && strtotime($request->toDate)) {
            $filters['toDate'] = date('Y-m-d', strtotime($request->toDate));
        }
        if (!empty($request->transactionTypeNumber) && strlen($request->transactionTypeNumber)) {
            $filters['transactionTypeNumber'] = $request->transactionTypeNumber;
        }

        $tsFromDate = strtotime('+23 hour +59 minutes +59 seconds',strtotime($filters['fromDate']));
        $filters['toDate'] = date("Y-m-t H:i:s", $tsFromDate);

        $transactionQuery = \App\Models\Transaction::query();
        $transactionQuery = $transactionQuery->where('isPaymentReceipt',1);

		if ($filters['isIgnoreDates'] != 1) {
            // $transactionQuery = $transactionQuery->whereBetween('dateCreated',[$filters['fromDate'],$filters['toDate']]);
			$transactionQuery = $transactionQuery->whereBetween('transactionDate',[$filters['fromDate'],$filters['toDate']]);
        }

        if (strlen($request->transactionTypeNumber)) {
            $transactionQuery = $transactionQuery->where('transactionTypeNumber','like','%' . $request->transactionTypeNumber . '%');
        }

        if (is_numeric($filters['headID'])) {
			$transactionQuery = $transactionQuery->whereIn('transactionID', function($transactionQuery) use ($filters) {
                return $transactionQuery->select(DB::raw('transactionID FROM transactionDetail WHERE headID = ' . $filters['headID']));
            });
        }

        if (is_numeric($filters['subHeadID'])) {
            $transactionQuery = $transactionQuery->whereIn('transactionID', function($transactionQuery) use ($filters) {
                return $transactionQuery->select(DB::raw('transactionID FROM transactionDetail WHERE subHeadID = ' . $filters['subHeadID']));
            });
            $filters['subHeadName'] = AccountHead::where('headID', $filters['subHeadID'])->pluck('headName')->first();
        }

        if (is_numeric($filters['customerID'])) {
            $transactionQuery = $transactionQuery->whereIn('transactionID', function($transactionQuery) use ($filters) {
                return $transactionQuery->select(DB::raw('transactionID FROM transactionDetail WHERE subHeadID IN (SELECT headID FROM customer WHERE customerID = ' . $filters['customerID'] . ')'));
            });
        }

        $transactions = $transactionQuery->orderBy('transactionDate', 'desc')->orderBy('transactionTypeNumber','desc')->get();

        $customers = \App\Models\Customer::orderBy('customerName','asc')->get();

		$filterHeads = \App\Models\AccountHead::whereIn('headID',[\Config::get('constants.account_heads.customer_receivable'),\Config::get('constants.account_heads.expense'),\Config::get('constants.account_heads.staff_receivable')])->orderBy('headName','asc')->get();

		foreach ($filterHeads as &$filterHead) {
		    if ($filterHead->headID == \Config::get('constants.account_heads.customer_receivable')) {
                $filterHead['filterHeadID'] = \Config::get('constants.account_heads.customer');
            } elseif ($filterHead->headID == \Config::get('constants.account_heads.staff_receivable')) {
                $filterHead['filterHeadID'] = \Config::get('constants.account_heads.staff');
            } else {
                $filterHead['filterHeadID'] = $filterHead->headID;
            }
        }

		$filterSubHeads = AccountHead::whereIn('parentHeadID', [\Config::get('constants.account_heads.customer'),\Config::get('constants.account_heads.staff'),\Config::get('constants.account_heads.expense')])->get();
		return view('admin.accountHead.showPaymentsReceipts',compact('transactions','filters','customers','filterHeads','filterSubHeads'));
    }

	public function newPayment(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$suppliers = \App\Models\Supplier::all()->sortBy('supplierName');
		$customers = \App\Models\Customer::all()->sortBy('customerName');
		$expenses = AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.expense') . ' AND isShowForPayment = 1')->orderBy('headName', 'asc')->get();
		$incomes = AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.revenue') . ' AND isShowForReceipt = 1')->orderBy('headName', 'asc')->get();
		$staffs = \App\Models\Staff::orderBy('staffName','asc')->get();
		$bankAccounts = \App\Models\BankAccount::all();
		$banks = \App\Models\Bank::all();
        $godowns = \App\Models\Godown::all();
        $transports = \App\Models\Transport::all();
		$isPayment = true;
        return view('admin.accountHead.createPayment', compact('suppliers','customers', 'expenses','staffs','bankAccounts','isPayment','godowns','transports'));
	}

	public function doPayment(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        DB::beginTransaction();
		try {
            $totalAmount = 0;
            $aryTransactionDetails = [];
            $txtPaidTo = " to " . $request->paymentTo;
            $transactionTypeNumber = null;
            if ($request->has('transactionTypeNumber')) {
                $transactionTypeNumber = $request->transactionTypeNumber;
            }
            switch ($request->paymentTo) {
                case 'supplier':
                    $subHeadID = \App\Models\Supplier::find($request->supplierID)->headID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.supplier_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment to Supplier'];
                    break;
                case 'customer':
                    $subHeadID = \App\Models\Customer::find($request->customerID)->headID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.customer_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment to Customer'];
                    break;
                case 'staff':
                case 'salaries':
                    $headID = \Config::get('constants.account_heads.staff_receivable');
                    if ($request->paymentTo == 'salaries') {
                        $headID = \Config::get('constants.account_heads.salaries_payable');
                        $txtPaidTo = " for salary";
                    }
                    $subHeadID = \App\Models\Staff::find($request->staffID)->headID;
                    $aryTransactionDetails['debit'] = ['headID' => $headID, 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment to Staff'];
                    break;
                case 'expense':
                    $subHeadID = $request->expenseHeadID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.expense'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment for Expense'];
                    $txtPaidTo = " for expense";
                    break;
                case 'godown':
                    $subHeadID = $request->godownHeadID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.accounts_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment for Godown'];
                    $txtPaidTo = " for godown";
                    break;
                case 'transport':
                    $subHeadID = $request->transportHeadID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.accounts_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment for Godown'];
                    $txtPaidTo = " for transport";
                    break;
                case 'bank':
                    $subHeadID = \App\Models\BankAccount::find($request->bankAccountID)->headID;
                    $aryTransactionDetails['debit'] = ['headID' => \Config::get('constants.account_heads.bank_accounts'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'description' => 'Payment to Bank'];
                    break;
                default:
                    $subHeadID = 0;
            }

            foreach ($request->paymentVia as $paidVia) {
                switch ($paidVia) {
                    case 'cash':
                        $aryTransactionDetails['credit'][] = ['headID' => \Config::get('constants.account_heads.cash'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'amount' => $request->cashAmount,'description' => 'Cash paid' . $txtPaidTo];
                        $totalAmount+=$request->cashAmount;
                        break;
                    case 'cheque':
                        foreach ($request->chequeAmount as $idx => $chequeAmount) {
                            $bankAccount = \App\Models\BankAccount::find($request->viaChequeBankAccountID[$idx]);
                            $subHeadID = $bankAccount->headID;
                            $chequeInfo = ['bankInstrumentTypeID' => \Config::get('constants.bank_instrument.type.cheque'), 'bankID' => $bankAccount->bankID, 'bankAccountID' => $bankAccount->bankAccountID, 'instrumentNumber' => $request->chequeNo[$idx],'instrumentDate' => $request->chequeDate[$idx],'instrumentAmount' => $chequeAmount,'description' => 'Payment via Cheque'];
                            $aryTransactionDetails['credit'][] = ['headID' => \Config::get('constants.account_heads.bank_accounts'),
                                                                    'subHeadID' => $subHeadID,'isDebit' => 0,'amount' => $chequeAmount,
                                                                    'description' => 'Cheque given' . $txtPaidTo, 'chequeInfo' => $chequeInfo];
                            $totalAmount+=$chequeAmount;
                        }
                        break;
                    case 'staff':
                        $subHeadID = \App\Models\Staff::find($request->viaStaffID)->headID;
                        $aryTransactionDetails['credit'][] = ['headID' => \Config::get('constants.account_heads.staff_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'amount' => $request->viaStaffAmount,'description' => 'Staff made the payment' . $txtPaidTo];
                        $totalAmount+=$request->viaStaffAmount;
                        break;
                    case 'bank':
                        $subHeadID = \App\Models\BankAccount::find($request->viaBankAccountID)->headID;
                        $aryTransactionDetails['credit'][] = ['headID' => \Config::get('constants.account_heads.bank_accounts'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'amount' => $request->viaBankAmount,'description' => 'Payment made via Bank' . $txtPaidTo];
                        $totalAmount+=$request->viaBankAmount;
                        break;
                    default:
                        echo 'Default';
                }
            }

            $aryTransactionDetails['debit']['amount'] = $totalAmount;

            if (empty($aryTransactionDetails['debit']) || empty($aryTransactionDetails['credit'])) {
                DB::rollback();
                $request->session()->flash('error', 'An error occurred while adding Payment transaction!');
                return redirect()->route('accountHead.payment');
            }

            $transactionID = \App\Services\TransactionService::addTransaction(1,1,null,$transactionTypeNumber,1,$request->transactionDate);

            $aryTransactionDetails['debit'] = Arr::add($aryTransactionDetails['debit'], 'transactionID', $transactionID);

			\App\Services\TransactionService::addTransactionDetailArray($aryTransactionDetails['debit']);

            foreach ($aryTransactionDetails['credit'] as $creditTransactions) {
                $creditTransactions = Arr::add($creditTransactions,'transactionID', $transactionID);
                $isChequeExists = false;
                if (Arr::has($creditTransactions,'chequeInfo')) {
                    $chequeInfo = $creditTransactions['chequeInfo'];
                    $isChequeExists = true;
                    $creditTransactions = Arr::except($creditTransactions,['chequeInfo']);
                }

				$transactionDetailID = \App\Services\TransactionService::addTransactionDetailArray($creditTransactions);

                if ($isChequeExists == true) {
                    $chequeInfo = Arr::add($chequeInfo,'transactionDetailID', $transactionDetailID);
                    $bankInstrumentDetailID = DB::table('bankInstrumentDetail')->insertGetId($chequeInfo);
                    DB::table('bankInstrumentStage')->insert(['bankInstrumentDetailID' => $bankInstrumentDetailID, 'bankInstrumentStatusID' => \Config::get('constants.bank_instrument.status.processing')]);
					DB::table('bankInstrumentStage')->insert(['bankInstrumentDetailID' => $bankInstrumentDetailID, 'bankInstrumentStatusID' => \Config::get('constants.bank_instrument.status.cleared')]);
                }
            }

			DB::commit();
			$request->session()->flash('message', 'Payment transaction added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while adding Payment transaction!');
		}
        return redirect()->route('accountHead.payment');
	}

    public function newReceipt(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$customers = \App\Models\Customer::all()->sortBy('customerName');
		$incomes = AccountHead::with('childrenAccountHeads')->whereRaw('parentHeadID = ' . \Config::get('constants.account_heads.revenue') . ' AND isShowForReceipt = 1')->get();
		$staffs = \App\Models\Staff::all();
		$bankAccounts = \App\Models\BankAccount::all();
		$banks = \App\Models\Bank::all();
        $isPayment = false;
        return view('admin.accountHead.createPayment', compact('customers','incomes','staffs','bankAccounts','isPayment'));
	}

    public function doReceipt(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
		try {
            $totalAmount = 0;
            $aryTransactionDetails = [];
            $txtPaidTo = " from " . $request->paymentTo;
            $transactionTypeNumber = null;
            if ($request->has('transactionTypeNumber')) {
                $transactionTypeNumber = $request->transactionTypeNumber;
            }
            switch ($request->paymentTo) {
                case 'customer':
                    $subHeadID = \App\Models\Customer::find($request->customerID)->headID;
                    $aryTransactionDetails['credit'] = ['headID' => \Config::get('constants.account_heads.customer_receivable'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'description' => 'Received from Customer'];
                    break;
                case 'staff':
                    $subHeadID = \App\Models\Staff::find($request->staffID)->headID;
                    $aryTransactionDetails['credit'] = ['headID' => \Config::get('constants.account_heads.staff_payable'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'description' => 'Received from Staff'];
                    break;
                case 'income':
                    $subHeadID = $request->incomeHeadID;
                    $aryTransactionDetails['credit'] = ['headID' => \Config::get('constants.account_heads.revenue'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'description' => 'Received from income'];
                    $txtPaidTo = " for expense";
                    break;
                case 'bank':
                    $subHeadID = \App\Models\BankAccount::find($request->bankAccountID)->headID;
                    $aryTransactionDetails['credit'] = ['headID' => \Config::get('constants.account_heads.bank_accounts'), 'subHeadID' => $subHeadID, 'isDebit' => 0,'description' => 'Received from Bank'];
                    break;
                default:
                    $subHeadID = 0;
            }

            foreach ($request->paymentVia as $paidVia) {
                switch ($paidVia) {
                    case 'cash':
                        $aryTransactionDetails['debit'][] = ['headID' => \Config::get('constants.account_heads.cash'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'amount' => $request->cashAmount,'description' => 'Cash received' . $txtPaidTo];
                        $totalAmount+=$request->cashAmount;
                        break;
                    case 'cheque':
                        foreach ($request->chequeAmount as $idx => $chequeAmount) {
                            $bankAccount = \App\Models\BankAccount::find($request->viaChequeBankAccountID[$idx]);
                            $subHeadID = $bankAccount->headID;
                            $chequeInfo = ['bankInstrumentTypeID' => \Config::get('constants.bank_instrument.type.cheque'), 'bankID' => $bankAccount->bankID, 'bankAccountID' => $bankAccount->bankAccountID, 'instrumentNumber' => $request->chequeNo[$idx],'instrumentDate' => $request->chequeDate[$idx],'instrumentAmount' => $chequeAmount,'description' => 'Received via Cheque'];
                            $aryTransactionDetails['debit'][] = ['headID' => \Config::get('constants.account_heads.bank_accounts'),
                                                                    'subHeadID' => $subHeadID,'isDebit' => 1,'amount' => $chequeAmount,
                                                                    'description' => 'Cheque received' . $txtPaidTo, 'chequeInfo' => $chequeInfo];
                            $totalAmount+=$chequeAmount;
                        }
                        break;
                    case 'bank':
                        $subHeadID = \App\Models\BankAccount::find($request->viaBankAccountID)->headID;
                        $aryTransactionDetails['debit'][] = ['headID' => \Config::get('constants.account_heads.bank_accounts'), 'subHeadID' => $subHeadID, 'isDebit' => 1,'amount' => $request->viaBankAmount,'description' => 'Received via Bank' . $txtPaidTo];
                        $totalAmount+=$request->viaBankAmount;
                        break;
                    default:
                        echo 'Default';
                }
            }

            $aryTransactionDetails['credit']['amount'] = $totalAmount;

            if (empty($aryTransactionDetails['debit']) || empty($aryTransactionDetails['credit'])) {
                DB::rollback();
                $request->session()->flash('error', 'An error occurred while adding Receipt transaction!');
                return redirect()->route('accountHead.receipt');
            }

            if ($request->paymentTo != 'customer') {
                $transactionTypeNumber = null;
            }

            $transactionID = \App\Services\TransactionService::addTransaction(2,1,null,$transactionTypeNumber,1,$request->transactionDate);

            foreach ($aryTransactionDetails['debit'] as $debitTransactions) {
                $debitTransactions = Arr::add($debitTransactions,'transactionID', $transactionID);
                $isChequeExists = false;
                if (Arr::has($debitTransactions,'chequeInfo')) {
                    $chequeInfo = $debitTransactions['chequeInfo'];
                    $isChequeExists = true;
                    $debitTransactions = Arr::except($debitTransactions,['chequeInfo']);
                }

				$transactionDetailID = \App\Services\TransactionService::addTransactionDetailArray($debitTransactions);

                if ($isChequeExists == true) {
                    $chequeInfo = Arr::add($chequeInfo,'transactionDetailID', $transactionDetailID);
                    $bankInstrumentDetailID = DB::table('bankInstrumentDetail')->insertGetId($chequeInfo);
                    DB::table('bankInstrumentStage')->insert(['bankInstrumentDetailID' => $bankInstrumentDetailID, 'bankInstrumentStatusID' => \Config::get('constants.bank_instrument.status.processing')]);
                }
            }

			$aryTransactionDetails['credit'] = Arr::add($aryTransactionDetails['credit'], 'transactionID', $transactionID);

			\App\Services\TransactionService::addTransactionDetailArray($aryTransactionDetails['credit']);

			DB::commit();
			$request->session()->flash('message', 'Receipt transaction added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while adding Receipt transaction!');
		}
        return redirect()->route('accountHead.receipt');
    }

	public function cheques(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$chequesStatuses = \App\Models\BankInstrumentStatus::all();
		$paymentCheques = [];
		$processingStatusID = \Config::get('constants.bank_instrument.status.processing');
		$receiptCheques = \App\Models\BankInstrumentDetail::with('transactionDetail.transaction','bankInstrumentStages.bankInstrumentStatus','bankInstrumentType','bank','bankAccount')->get();
        return view('admin.accountHead.cheques', compact('processingStatusID','chequesStatuses','paymentCheques','receiptCheques'));
	}

	public function updateCheques(int $bankInstrumentDetailID,int $bankInstrumentStatusID,Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		//	Put entry in bankInstrumentStage table
        DB::beginTransaction();
		try {
			DB::table('bankInstrumentStage')->insert(['bankInstrumentDetailID' => $bankInstrumentDetailID, 'bankInstrumentStatusID' => $bankInstrumentStatusID]);
			//	if status == bounced/dishonoured then
			if (in_array($bankInstrumentStatusID,\Config::get('constants.bank_instrument.bad_status'))) {
				//	Get the transactionDetail and reverse that entry
				$transactionDetailID = \App\Models\BankInstrumentDetail::find($bankInstrumentDetailID)->transactionDetailID;
				$transactionDetail = \App\Models\TransactionDetail::find($transactionDetailID);

				$transactionDetail1 = $transactionDetail->replicate();
				$transactionDetail2 = \App\Models\TransactionDetail::where('transactionID',$transactionDetail->transactionID)->where('isDebit',!$transactionDetail->isDebit)->first()->replicate();

				$transactionDetail1->isDebit = !$transactionDetail1->isDebit;
				$transactionDetail2->isDebit = !$transactionDetail2->isDebit;

				$transactionDetail1->description = "Cheque bounced/dishonoured";
				$transactionDetail2->description = "Cheque bounced/dishonoured";

				$transactionDetail2->amount = $transactionDetail1->amount;

				$transaction = \App\Models\Transaction::find($transactionDetail->transactionID)->replicate();
				$transaction->save();

				$transactionDetail1->transactionID = $transaction->transactionID;
				$transactionDetail2->transactionID = $transaction->transactionID;

				$transactionDetail1->save();
				$transactionDetail2->save();
			}
			DB::commit();
			$request->session()->flash('message', 'Cheque Status changed successfully!');
		} catch (\Exception $e) {
			DB::rollback();
            dd($e);
			$request->session()->flash('error', 'An error occurred while changing cheque status!');
		}

		return redirect()->route('accountHead.cheques');
	}

	public function openingBalance(Request $request) {
		abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$openingBalanceHeads = \App\Models\AccountHead::with('childrenAccountHeads')->where('isShowForOpeningBalance',1)->get();
		$openingBalances = \App\Models\OpeningBalance::all();
        return view('admin.accountHead.formOpeningBalance', compact('openingBalances','openingBalanceHeads'));
	}

	public function updateOpeningBalance(Request $request) {
		DB::beginTransaction();
		try {
            if ($request->openingBalanceIDs != null) {
                $openingBalancesToDelete = \App\Models\OpeningBalance::whereNotIn('openingBalanceID', $request->openingBalanceIDs)->get();
                foreach ($openingBalancesToDelete as $openingBalance) {
    				$OPObject = \App\Models\OpeningBalance::find($openingBalance->openingBalanceID);
    				$OPObject->delete();
    				\App\Services\TransactionService::deleteTransaction($openingBalance->transactionID);
    			}
            }

			if ($request->has('headID')) {
				foreach ($request->headID as $idx => $subHead) {
					$parentAccountHeadID = \App\Models\AccountHead::whereRaw('headID IN (SELECT parentHeadID from accountHead WHERE headID = ' . $request->headID[$idx] . ')')->first()->headID;
					$transactionDetailDebit = ['isDebit' => 1,'headID' => $parentAccountHeadID,'amount' => abs($request->amount[$idx]), 'subHeadID' => $subHead, 'description' => 'Opening Balance'];
					$transactionDetailCredit = ['isDebit' => 0,'headID' => $parentAccountHeadID,'amount' => abs($request->amount[$idx]), 'subHeadID' => $subHead, 'description' => 'Opening Balance'];

					switch ($parentAccountHeadID) {
						// Customer
						case \Config::get('constants.account_heads.customer'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.customer_receivable'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.sales'));
							} else {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.purchases'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.customer_payable'));
							}
							break;
						case \Config::get('constants.account_heads.supplier'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.supplier_receivable'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.sales'));
							} else {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.purchases'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.supplier_payable'));
							}
							break;
						case \Config::get('constants.account_heads.bank_accounts'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.bank_accounts'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.owner_capital'));
							} else {
								dd("Bank Account cannot have less than 0 balance.");
							}
							break;
						case \Config::get('constants.account_heads.cash'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.cash'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.owner_capital'));
							} else {
								dd("Cash cannot have less than 0 balance.");
							}
							break;
						case \Config::get('constants.account_heads.godown'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.godown_receivable'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.service_revenue'));
							} else {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.service_expense'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.godown_payable'));
							}
							break;
						case \Config::get('constants.account_heads.transport'):
							if ($request->amount[$idx] > 0) {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.transport_receivable'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.service_revenue'));
							} else {
								$transactionDetailDebit = Arr::set($transactionDetailDebit,'headID', \Config::get('constants.account_heads.service_expense'));
								$transactionDetailCredit = Arr::set($transactionDetailCredit,'headID', \Config::get('constants.account_heads.transport_payable'));
							}
							break;
						default:
							dd('Invalid Option. Please contact admin');
					}

					$transactionID = \App\Services\TransactionService::addTransaction(1, 1, null, null, 0,$request->transactionDate[$idx]);

					\App\Models\OpeningBalance::create(['batchID' => \App\Services\BatchService::getCurrentBatch()->batchID,'transactionID' => $transactionID,'createdByUserID' => Auth::id()]);

					$transactionDetailDebit = Arr::add($transactionDetailDebit,'transactionID', $transactionID);
					$transactionDetailCredit = Arr::add($transactionDetailCredit,'transactionID', $transactionID);

					\App\Services\TransactionService::addTransactionDetailArray($transactionDetailDebit);
					\App\Services\TransactionService::addTransactionDetailArray($transactionDetailCredit);
				}
			}
			DB::commit();
			$request->session()->flash('message', 'Opening Balance updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while updating opening balance!');
		}

		return redirect()->route('accountHead.openingBalance');
	}

	public function trialBalance(Request $request) {
		abort_if(Gate::denies('transaction_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$transactionDetails = \App\Models\TransactionDetail::all();
		return view('admin.accountHead.trialBalance', compact('transactionDetails'));
	}

    public function transactionDestroy(Request $request) {
        abort_if(Gate::denies('transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transaction = \App\Models\Transaction::find($request->transactionID);
        DB::beginTransaction();
		try {
			$transaction->delete();
			DB::commit();
			$request->session()->flash('message', 'Transaction deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting Transaction!');
		}
		return redirect()->route('accountHead.paymentsReceipts');
	}
}
