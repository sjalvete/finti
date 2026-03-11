<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $chapters = Chapter::query()
            ->where('project_id', $projectId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('chapters.index', [
            'chapters' => $chapters,
        ]);
    }

    public function store(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
        ]);

        $position = (Chapter::where('project_id', $projectId)->max('position') ?? 0) + 1;

        Chapter::create([
            'project_id' => $projectId,
            'name' => $data['name'],
            'synopsis' => $data['synopsis'] ?? null,
            'position' => $position,
        ]);

        return redirect()->route('chapters.index');
    }

    public function update(Request $request, Chapter $chapter)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($chapter->project_id === $projectId, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
        ]);

        $chapter->update([
            'name' => $data['name'],
            'synopsis' => $data['synopsis'] ?? null,
        ]);

        return redirect()->route('chapters.index');
    }

    public function destroy(Request $request, Chapter $chapter)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($chapter->project_id === $projectId, 403);

        if ($chapter->sections()->exists()) {
            return back()->withErrors([
                'chapter' => 'Cannot delete a chapter that still contains sections.',
            ]);
        }

        $chapter->delete();

        return redirect()->route('chapters.index');
    }

    public function reorder(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $data = $request->validate([
            'chapters' => ['required', 'array'],
            'chapters.*' => ['integer'],
        ]);

        $allowedIds = Chapter::query()
            ->where('project_id', $projectId)
            ->pluck('id')
            ->all();

        $allowed = array_flip($allowedIds);

        DB::transaction(function () use ($data, $allowed, $projectId) {
            foreach ($data['chapters'] as $index => $chapterId) {
                if (!isset($allowed[$chapterId])) {
                    continue;
                }

                Chapter::where('project_id', $projectId)
                    ->where('id', $chapterId)
                    ->update(['position' => $index + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }
}
