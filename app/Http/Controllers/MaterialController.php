<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function show(Request $request, Material $material)
    {
        abort_unless($request->user()->can('view', $material) && $material->is_published, 403);

        if ($material->type === 'url') {
            return redirect()->away($material->url);
        }

        return response()->download(Storage::disk('local')->path($material->path));
    }
}
