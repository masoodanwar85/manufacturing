<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreBankAccountRequest;
use App\Http\Requests\UpdateBankAccountRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		abort_if(Gate::denies('bank_account_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = BankAccount::with('bank')->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'bank_account_read';
                $editGate      = 'bank_account_update';
                $deleteGate    = 'bank_account_delete';
                $crudRoutePart = 'bankAccount';
                $primaryKey = 'bankAccountID';
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('bank', function ($row) {
                return $row->bank->bankName ? $row->bank->bankName : "";
            });
            $table->editColumn('accountTitle', function ($row) {
                return $row->accountTitle ? $row->accountTitle : "";
            });
			$table->editColumn('accountNumber', function ($row) {
                return $row->accountNumber ? $row->accountNumber : "";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.bankAccount.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('bank_account_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banks = \App\Models\Bank::all()->sortBy('bankName');
		return view('admin.bankAccount.create',compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankAccountRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$request->request->add(['headID' => \App\Models\AccountHead::addAccountHead($request->accountTitle . ' (' . $request->accountNumber . ') '. ' (BankAccount)',Auth::id(),\Config::get('constants.account_heads.bank_accounts'),1,0,0,0,0,0)]);
	        $bankAccount = BankAccount::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Bank Account created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating bank Account!');
		}
		return redirect()->route('bankAccount.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
		abort_if(Gate::denies('bank_account_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.bankAccount.show', compact('bankAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
		abort_if(Gate::denies('bank_account_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $banks = \App\Models\Bank::all()->sortBy('bankName');
        return view('admin.bankAccount.edit', compact('bankAccount','banks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount)
    {
		DB::beginTransaction();
		try {
			\App\Models\AccountHead::updateAccountHead($bankAccount->headID,$request->accountTitle . ' (' . $request->accountNumber . ') '. ' (BankAccount)');
			$bankAccount->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Bank Account updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating bank Account!');
		}
		return redirect()->route('bankAccount.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount,Request $request)
    {
		abort_if(Gate::denies('bank_account_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$bankAccount->delete();
			$bankAccount->head->delete();
			DB::commit();
			$request->session()->flash('message', 'Bank Account deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			$request->session()->flash('error', 'An error occurred while deleting bank Account!');
		}

        return redirect()->route('bankAccount.index');
    }
}
