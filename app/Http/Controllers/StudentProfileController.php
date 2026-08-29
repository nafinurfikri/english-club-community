<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('student.profile', ['student' => $request->user()]);
    }

    public function update(Request $request)
    {
        $student = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->id)],
        ]);
        $student->update($data);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}
