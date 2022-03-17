<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_category_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Category::withCount('products')->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_category_read';
                $editGate      = 'product_category_update';
                $deleteGate    = 'product_category_delete';
                $crudRoutePart = 'category';
                $primaryKey = 'categoryID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('categoryName', function ($row) {
                return $row->categoryName ? $row->categoryName : "";
            });
            $table->editColumn('products_count', function ($row) {
                return $row->products_count ? $row->products_count : "0";
            });
            $table->editColumn('dateCreated', function ($row) {
                return $row->dateCreated ? $row->dateCreated : "";
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('product_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $request->request->add(['createdByUserID' => Auth::id()]);
        $category = Category::create($request->all());
        $request->session()->flash('message', 'Category added successfully!');
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
		abort_if(Gate::denies('product_category_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
		abort_if(Gate::denies('product_category_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        $request->session()->flash('message', 'Category updated successfully!');
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Request $request)
    {
		abort_if(Gate::denies('product_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if (count($category->products->toArray()) >= 1) {
			$request->session()->flash('warning', 'Category cannot be deleted due to assigned Products!');
		} else {
			$category->delete();
			$role->delete();
			$request->session()->flash('message', 'Category deleted successfully!');
		}

        return redirect()->route('category.index');
    }
}
