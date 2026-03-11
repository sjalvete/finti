<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SectionType;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $types = \App\Models\SectionType::query()
            ->where('project_id', $projectId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $chapters = \App\Models\Chapter::query()
            ->where('project_id', $projectId)
            ->with([
                'sections' => function ($query) {
                    $query->with('type')
                        ->orderBy('sort_order')
                        ->orderBy('id');
                },
            ])
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $orphaned = Section::query()
            ->where('project_id', $projectId)
            ->whereNull('chapter_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $progressOptions = [
            'planned' => 'Planned',
            'draft' => 'Draft',
            'rev1' => 'Rev 1',
            'rev2' => 'Rev 2',
            'final' => 'Final',
            'issue' => 'Issue',
        ];

        $stepMap = [
            'planned' => 0,
            'draft' => 1,
            'rev1' => 2,
            'rev2' => 2,
            'final' => 3,
            'issue' => 1,
        ];

        return view('sections.index', [
            'chapters' => $chapters,
            'orphaned' => $orphaned,
            'types' => $types,
            'progressOptions' => $progressOptions,
            'stepMap' => $stepMap,
        ]);
    }

    public function store(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'chapter_id' => ['nullable', 'integer'],
            'section_type_id' => ['nullable', 'integer'],
            'progress_status' => ['required', 'in:planned,draft,rev1,rev2,final,issue'],
        ]);

        if (!empty($data['chapter_id'])) {
            $chapterExists = \App\Models\Chapter::query()
                ->where('project_id', $projectId)
                ->where('id', $data['chapter_id'])
                ->exists();

            abort_unless($chapterExists, 403);
        }

        $data['project_id'] = $projectId;

        $max = Section::where('project_id', $projectId)->max('sort_order');
        $data['sort_order'] = is_null($max) ? 0 : ($max + 10);
        $data['chapter_id'] = $data['chapter_id'] ?: null;
        $data['section_type_id'] = $data['section_type_id'] ?: null;

        Section::create($data);

        return redirect()->route('sections.index');
    }

    public function update(Request $request, Section $section)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($section->project_id === $projectId, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'chapter_id' => ['nullable', 'integer'],
            'section_type_id' => ['nullable', 'integer'],
            'progress_status' => ['required', 'in:planned,draft,rev1,rev2,final,issue'],
        ]);

        if (!empty($data['chapter_id'])) {
            $chapterExists = \App\Models\Chapter::query()
                ->where('project_id', $projectId)
                ->where('id', $data['chapter_id'])
                ->exists();

            abort_unless($chapterExists, 403);
        }

        $section->update([
            'title' => $data['title'],
            'synopsis' => $data['synopsis'] ?? null,
            'body' => $data['body'] ?? null,
            'chapter_id' => $data['chapter_id'] ?: null,
            'section_type_id' => $data['section_type_id'] ?: null,
            'progress_status' => $data['progress_status'],
        ]);

        return redirect()->route('sections.index');
    }

    public function updateType(Section $section, Request $request)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($section->project_id === $projectId, 403);

        $data = $request->validate([
            'section_type_id' => ['nullable', 'integer'],
        ]);

        $section->update([
            'section_type_id' => $data['section_type_id'] ?: null,
        ]);

        return redirect()->route('sections.index');
    }

    public function reorder(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        // only allow reordering within current project (top-level for now)
        $allowedIds = \App\Models\Section::query()
            ->where('project_id', $projectId)
            ->pluck('id')
            ->all();

        $allowed = array_flip($allowedIds);

        DB::transaction(function () use ($data, $allowed, $projectId) {
            $order = 0;
            foreach ($data['ids'] as $id) {
                if (!isset($allowed[$id])) {
                    continue;
                }
                \App\Models\Section::where('project_id', $projectId)->where('id', $id)
                    ->update(['sort_order' => $order]);
                $order += 10;
            }
        });

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, Section $section)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($section->project_id === $projectId, 403);

        $section->delete();

        return redirect()->route('sections.index');
    }

    private static function colorKeys(): array
    {
        return [
            'gray' => 'Gray',
            'red' => 'Red',
            'orange' => 'Orange',
            'yellow' => 'Yellow',
            'green' => 'Green',
            'teal' => 'Teal',
            'blue' => 'Blue',
            'indigo' => 'Indigo',
            'violet' => 'Violet',
            'pink' => 'Pink',
        ];
    }
}
