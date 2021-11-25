<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('roles_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if ($request->ajax()) {
            $query = Roles::with('privileges.accessLevel')->get();
            $table = Datatables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'roles_read';
                $editGate      = 'roles_update';
                $deleteGate    = 'roles_delete';
                $crudRoutePart = 'role';
                $primaryKey = 'roleID';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row',
                    'primaryKey'
                ));
            });

            $table->editColumn('roleName', function ($row) {
                return $row->roleName;
            });
            // $table->addColumn('description', function ($row) {
            //     return $row->description;
            // });
            $table->addColumn('privileges', function ($row) {
                $privileges = [];
                foreach ($row->privileges as $rolePrivileges) {
                    $privileges[] = sprintf('<span class="badge bg-info">%s %s</span>', ucwords(strtolower(strtr($rolePrivileges->privilegeCode,'_',' '))),$rolePrivileges->accessLevel->accessLevel);
                }
                return implode(' ', $privileges);
            });
            // $table->addColumn('dateCreated', function ($row) {
            //     return $row->dateCreated;
            // });

            $table->rawColumns(['actions','privileges']);

            return $table->make(true);
        }
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		abort_if(Gate::denies('roles_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$accessLevels = \App\Models\AccessLevel::all();
		$privileges = \App\Models\Privilege::with(['module','accessLevel'])->get();
		return view('admin.role.create',compact('accessLevels','privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
		DB::beginTransaction();
		try {
			$role = Roles::create($request->all());
			$role->privileges()->attach($request->privilegeID);
			DB::commit();
			$request->session()->flash('message', 'Role added successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while adding role!');
		}

        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $role)
    {
		abort_if(Gate::denies('roles_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$role = Roles::with('privileges.module')->find($role->roleID);
        return view('admin.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Roles $role)
    {
		abort_if(Gate::denies('roles_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$accessLevels = \App\Models\AccessLevel::all();
		$privileges = \App\Models\Privilege::with(['module','accessLevel'])->get();
		$rolePrivileges = \App\Models\RolePrivilege::where('roleID', $role->roleID)->get()->map(function ($item, $key) {
			return $item->privilegeID;
		})->toArray();
		return view('admin.role.edit',compact('role','accessLevels','privileges','rolePrivileges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Roles $role)
    {
		DB::beginTransaction();
		try {
			$role->update($request->all());
			$role->privileges()->detach();
			$role->privileges()->attach($request->privilegeID);
			DB::commit();
			$request->session()->flash('message', 'Role updated successfully!');
		} catch (\Exception $e) {
			DB::rollback();
			$request->session()->flash('error', 'An error occurred while updating role!');
		}

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roles $role, Request $request)
    {
		abort_if(Gate::denies('roles_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		if (count($role->users->toArray()) >= 1) {
			$request->session()->flash('warning', 'Role cannot be deleted due to assigned User!');
		} else {
			DB::beginTransaction();
			try {
				$role->privileges()->detach();
				$role->delete();
				DB::commit();
				$request->session()->flash('message', 'Role deleted successfully!');
			} catch (\Exception $e) {
				DB::rollback();
				$request->session()->flash('error', 'An error occurred while deleting role!');
			}
		}
		return redirect()->route('role.index');
    }
}
