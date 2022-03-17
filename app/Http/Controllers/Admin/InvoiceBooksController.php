<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceBooks;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreInvoiceBooksRequest;
use App\Http\Requests\UpdateInvoiceBooksRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InvoiceBooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = InvoiceBooks::withCount('serials')->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'invoice_books_read';
                $editGate      = 'invoice_books_update';
                $deleteGate    = 'invoice_books_delete';
                $crudRoutePart = 'invoiceBooks';
                $primaryKey = 'invoiceBookID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('bookType', function ($row) {
                return $row->bookType;
            });
			$table->editColumn('bookNumber', function ($row) {
                return $row->bookNumber;
            });
            $table->editColumn('bookStartPage', function ($row) {
                return $row->startPage;
            });
            $table->editColumn('bookEndPage', function ($row) {
                return $row->endPage;
            });
			$table->editColumn('invalidatedSerials', function ($row) {
                return $row->serials_count;
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.invoiceBooks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('invoice_books_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.invoiceBooks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceBooksRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['createdByUserID' => Auth::id()]);
			$invoiceBooks = InvoiceBooks::create($request->all());
			DB::commit();
			$request->session()->flash('message', 'Invoice Book created successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while creating Invoice Book!');
		}
		return redirect()->route('invoiceBooks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceBooks  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceBooks $invoiceBook)
    {
		abort_if(Gate::denies('invoice_books_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoiceBook = $invoiceBook->where('invoiceBookID',$invoiceBook->invoiceBookID)->with('serials')->first();
        return view('admin.invoiceBooks.show', compact('invoiceBook'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceBooks  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceBooks $invoiceBook)
    {
		abort_if(Gate::denies('invoice_books_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return view('admin.invoiceBooks.edit', compact('invoiceBook'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceBooks  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceBooksRequest $request, InvoiceBooks $invoiceBook)
    {
		DB::beginTransaction();
		try {
			$invoiceBook->update($request->all());
			DB::commit();
			$request->session()->flash('message', 'Invoice Book updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while Invoice Book!');
		}
        return redirect()->route('invoiceBooks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceBooks  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceBooks $invoiceBook, Request $request)
    {
		abort_if(Gate::denies('invoice_books_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$invoiceBook->delete();
			DB::commit();
			$request->session()->flash('message', 'Invoice Book deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting Invoice Book!');
		}

        return redirect()->route('invoiceBooks.index');
    }

    public function updateBookSerials(Request $request) {
		abort_if(Gate::denies('account_head_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $invoiceBook = InvoiceBooks::find($request->get('invoiceBookID'));
        if ($request->serialNumber != null) {
            $aryBookSerials = [];
            foreach ($request->serialNumber as $idx => $serial) {
    			array_push($aryBookSerials,[
    				'serialNumber' => $serial,
    				'reason' => $request->reason[$idx],
    				'createdByUserID' => Auth::id()
    			]);
            }
        }

        DB::beginTransaction();
		try {
            $invoiceBook->serials()->delete();
            if ($request->serialNumber != null) {
                $invoiceBook->serials()->createMany($aryBookSerials);
            }
			DB::commit();
			$request->session()->flash('message', 'Book Serials Voids updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while Updating Serials Voids!');
		}

        return redirect()->route('invoiceBooks.show',$request->get('invoiceBookID'));
	}

    public function getNextSerialNumber($bookType) {
        $nextSerial = InvoiceBooks::getInvoiceBooksMissingSerialNumbers(['bookType' => $bookType, 'nextSerial' => 1]);
        if (!empty($nextSerial)) {
            return $nextSerial[0]->serial;
        } else {
            return NULL;
        }
	}
}
