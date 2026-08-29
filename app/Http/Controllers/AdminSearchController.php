<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));

        $results = [
            'students' => [],
            'subjects' => [],
            'announcements' => [],
            'materials' => [],
        ];

        if ($query !== '') {
            $results['students'] = User::where('role', 'siswa')
                ->where(fn ($builder) => $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%"))
                ->orderBy('name')
                ->limit(20)
                ->get();

            $results['subjects'] = Subject::where(fn ($builder) => $builder->where('name', 'like', "%{$query}%")
                ->orWhere('teacher', 'like', "%{$query}%")
                ->orWhere('level', 'like', "%{$query}%"))
                ->orderBy('name')
                ->limit(20)
                ->get();

            $results['announcements'] = Announcement::where('title', 'like', "%{$query}%")
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $results['materials'] = Material::with('clubSession:id,title')
                ->where('title', 'like', "%{$query}%")
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();
        }

        return view('admin.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
