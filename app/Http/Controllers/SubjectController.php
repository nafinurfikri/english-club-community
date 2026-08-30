<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Material;
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
            'subjects' => Subject::with('materials')->orderBy('sort_order')->orderBy('name')->get(),
            'allSubjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function storeMaterial(Subject $subject, Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:file,url'],
            'file' => ['required_if:type,file', 'nullable', 'file', 'max:20480'],
            'url' => ['required_if:type,url', 'nullable', 'url', 'max:2048'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $subject->materials()->create([
            'title' => $data['title'],
            'type' => $data['type'],
            'path' => $data['type'] === 'file' ? $request->file('file')->store('materials') : null,
            'url' => $data['type'] === 'url' ? $data['url'] : null,
            'is_published' => ! empty($data['published']),
        ]);

        return back()->with('status', 'Materi mata pelajaran berhasil disimpan.');
    }

    public function updateMaterial(Material $material, Request $request)
    {
        abort_unless($material->subject_id !== null, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $material->update([
            'title' => $data['title'],
            'is_published' => ! empty($data['published']),
        ]);

        return back()->with('status', 'Materi mata pelajaran berhasil diperbarui.');
    }

    public function destroyMaterial(Material $material)
    {
        abort_unless($material->subject_id !== null, 404);

        if ($material->path) {
            Storage::disk('local')->delete($material->path);
        }

        $material->delete();

        return back()->with('status', 'Materi mata pelajaran berhasil dihapus.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['materials' => fn ($q) => $q->where('is_published', true), 'sessions.materials']);

        $sessionMaterials = $subject->sessions
            ->flatMap(fn ($session) => $session->materials->where('is_published', true));

        $attended = auth()->user() && Attendance::where('user_id', auth()->id())
            ->where('status', Attendance::STATUS_HADIR)
            ->whereHas('clubSession', fn ($q) => $q->where('subject_id', $subject->id))
            ->exists();

        return view('student.subject-detail', [
            'subject' => $this->presentForCard($subject),
            'subjectMaterials' => $subject->materials,
            'sessionMaterials' => $sessionMaterials,
            'attended' => $attended,
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
