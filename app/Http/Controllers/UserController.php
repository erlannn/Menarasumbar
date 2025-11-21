<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            // gunakan rule bawaan: alpha_dash + unique
            'username' => ['required','string','alpha_dash','between:3,30','unique:users,username'],
            'password' => ['required','min:6','confirmed'],
            'role'     => ['required', Rule::exists('roles','name')],
        ],
        [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
        ], 
        [
            'name' => 'Nama Pengguna',
            'username' => 'Username',
            'password' => 'Password',
            'role' => 'Role'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required','string','max:255',
            Rule::unique('users','name')->ignore($user->id),
        ],

            // ignore user saat ini di unique check
            'username' => [
                'required','string','alpha_dash','between:3,30',
                Rule::unique('users','username')->ignore($user->id),
            ],
            // opsional ganti password
            'password' => ['nullable','min:6','confirmed'],
            'role'     => ['required', Rule::exists('roles','name')],
        ]);

        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->syncRoles([$request->role]);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
