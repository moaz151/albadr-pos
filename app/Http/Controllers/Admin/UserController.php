<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserStatusEnum;
use App\Http\Requests\Admin\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list-User')->only(['index']);
        $this->middleware('can:create-User')->only(['create', 'store']);
        $this->middleware('can:view-User')->only(['show']);
        $this->middleware('can:edit-User')->only(['edit', 'update']);
        $this->middleware('can:delete-User')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userStatuses = UserStatusEnum::labels();
        $roles = Role::all();
        $permissions = Permission::orderBy('group_name')->orderBy('display_name')->get()->groupBy('group_name');
        return view('admin.users.create', compact('userStatuses', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        $user->roles()->sync($request->roles);

        if ($request->filled('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $user->syncPermissions($permissions);
        } else {
            $user->syncPermissions([]);
        }
        return back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $userStatuses = UserStatusEnum::labels();
        $roles = Role::all();
        $permissions = Permission::orderBy('group_name')->orderBy('display_name')->get()->groupBy('group_name');
        $userPermissions = $user->permissions->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'userStatuses', 'roles', 'permissions', 'userPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user = User::findOrFail($id);
        $user->update($data);
        $user->roles()->sync($request->roles);

        // Sync direct permissions (optional)
        if ($request->filled('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $user->syncPermissions($permissions);
        } else {
            $user->syncPermissions([]);
        }
        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
