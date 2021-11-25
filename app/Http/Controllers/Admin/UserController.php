<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = User::with(['userType', 'roles'])->get();
            $table = Datatables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'user_read';
                $editGate      = 'user_update';
                $deleteGate    = 'user_delete';
                $crudRoutePart = 'user';
                $primaryKey = 'userID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name;
            });
            $table->addColumn('email', function ($row) {
                return $row->email;
            });
            $table->addColumn('dateCreated', function ($row) {
                return $row->dateCreated;
            });

            $table->editColumn('userType', function ($row) {
                return $row->userType->userType;
            });

            $table->editColumn('roles', function ($row) {
                $labels = [];

                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="badge badge-info">%s</span>', $role->roleName);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions','roles']);

            return $table->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$roles = \App\Models\Roles::all()->sortBy('roleName');
        return view('admin.user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
		DB::beginTransaction();
		try {
			$request->request->add(['userTypeID' => 2]);
			$request->request->add(['clientID' => 1]);
			$request->merge([
				'password' => Hash::make($request->password)
			]);
	        $user = User::create($request->all());
			$user->roles()->attach($request->roleID);
			DB::commit();
			$request->session()->flash('message', 'User added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while adding user!');
		}

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
		abort_if(Gate::denies('user_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
		abort_if(Gate::denies('user_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$roles = \App\Models\Roles::all()->sortBy('roleName');
		$userRoles = $user->roles->map(function ($item, $key) {
			return $item->roleID;
		})->toArray();
        return view('admin.user.edit',compact('user','roles','userRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
		DB::beginTransaction();
		try {
			$newPassword = $user->password;
			if (strlen(trim($request->password))) {
				$newPassword = Hash::make($request->password);
			}
			$request->merge([
				'password' => $newPassword
			]);
			$user->update($request->all());
			$user->roles()->sync($request->roleID);
			DB::commit();
			$request->session()->flash('message', 'User updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating user!');
		}

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
		abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		DB::beginTransaction();
		try {
			$user->roles()->detach();
			$user->delete();
			DB::commit();
			$request->session()->flash('message', 'User deleted successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while deleting user!');
		}
        return redirect()->route('user.index');
    }
}
