<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact([
            'users'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create($request->validated());
        return redirect()->route('admin.users.index')
            ->with('success', 'Users Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact(['user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                     'name' => 'required|string|max:255,'. $user->id,
                     'email' => 'required|email|unique:users,email,'.$user->id
            ]
            );
            $user->update($validated);
            return redirect()->route('admin.users.index')
                        ->with('success',"User updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User Deleted Successfully!');
    }
}
