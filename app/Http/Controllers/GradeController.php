<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\GradeCategory;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        return view('student.grades', [
            'grades' => Grade::with('category')
                ->where('user_id', $request->user()->id)
                ->whereNotNull('published_at')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'grade_category_id' => ['required', 'exists:grade_categories,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'published' => ['sometimes', 'boolean'],
        ]);

        Grade::create([
            ...$data,
            'published_at' => ! empty($data['published']) ? now() : null,
        ]);

        return back()->with('status', 'Nilai berhasil disimpan.');
    }

    public function adminIndex(Request $request)
    {
        $grades = Grade::with(['category', 'student'])
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('grade_category_id'), fn ($query) => $query->where('grade_category_id', $request->integer('grade_category_id')))
            ->latest()
            ->get();

        return view('admin.grades', [
            'grades' => $grades,
            'students' => User::where('role', 'siswa')->orderBy('name')->get(),
            'categories' => GradeCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'grade_category_id' => ['required', 'exists:grade_categories,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $grade->update([
            ...$data,
            'published_at' => ! empty($data['published']) ? ($grade->published_at ?? now()) : null,
        ]);

        return back()->with('status', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return back()->with('status', 'Nilai berhasil dihapus.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:grade_categories,name']]);
        GradeCategory::create($data);

        return back()->with('status', 'Kategori nilai berhasil dibuat.');
    }

    public function updateCategory(Request $request, GradeCategory $gradeCategory)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:grade_categories,name,'.$gradeCategory->id]]);
        $gradeCategory->update($data);

        return back()->with('status', 'Kategori nilai berhasil diperbarui.');
    }

    public function destroyCategory(GradeCategory $gradeCategory)
    {
        if ($gradeCategory->grades()->exists()) {
            return back()->withErrors(['category' => 'Kategori yang masih digunakan oleh nilai tidak dapat dihapus.']);
        }

        $gradeCategory->delete();

        return back()->with('status', 'Kategori nilai berhasil dihapus.');
    }
}
