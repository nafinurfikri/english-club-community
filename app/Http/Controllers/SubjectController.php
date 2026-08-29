<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function studentIndex()
    {
        $subjects = Subject::where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('student.subjects', [
            'subjects' => $subjects->map(fn (Subject $subject) => $this->presentForCard($subject)),
        ]);
    }

    public function adminIndex()
    {
        return view('admin.subjects', [
            'subjects' => Subject::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:100'],
            'teacher' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'published' => ['sometimes', 'boolean'],
        ]);

        Subject::create([
            ...$data,
            'image_path' => $request->file('image')?->store('subjects', 'public'),
            'is_published' => ! empty($data['published']),
        ]);

        return back()->with('status', 'Mata pelajaran berhasil disimpan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:100'],
            'teacher' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $subject->update([
            ...$data,
            'image_path' => $request->file('image')?->store('subjects', 'public') ?? $subject->image_path,
            'is_published' => ! empty($data['published']),
        ]);

        return back()->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->image_path) {
            Storage::disk('public')->delete($subject->image_path);
        }

        $subject->delete();

        return back()->with('status', 'Mata pelajaran berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function presentForCard(Subject $subject): array
    {
        return [
            'id' => $subject->id,
            'title' => $subject->name,
            'level' => $subject->level,
            'teacher' => $subject->teacher,
            'desc' => $subject->description,
            'img' => $subject->image_path
                ? Storage::disk('public')->url($subject->image_path)
                : 'https://picsum.photos/seed/ec-'.str($subject->name)->slug('-').'/640/300',
        ];
    }
}
