<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserStatusEnum;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userStatuses = UserStatusEnum::labels();
        return view('admin.users.create', compact('userStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated());
        session()->flash('success', 'User created successfully.');
        return redirect()->route('admin.users.index');
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
        return view('admin.users.edit', compact('user', 'userStatuses'));
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
        session()->flash('success', 'User updated successfully.');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
