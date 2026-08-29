<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStudentController extends Controller
{
    public function index()
    {
        return view('admin.students', [
            'students' => User::where('role', 'siswa')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? 'password'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        return back()->with('status', 'Data siswa berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, User $user)
    {
        abort_unless($user->role === 'siswa', 404);
        $data = $request->validate(['status' => ['required', 'in:active,pending,rejected']]);
        $user->update($data);

        return back()->with('status', 'Status siswa berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'siswa', 404);
        $user->delete();

        return back()->with('status', 'Data siswa berhasil dihapus.');
    }
}
