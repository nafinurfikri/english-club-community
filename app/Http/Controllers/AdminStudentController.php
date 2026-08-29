<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index()
    {
        return view('admin.students', [
            'students' => User::where('role', 'siswa')->latest()->get(),
        ]);
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
