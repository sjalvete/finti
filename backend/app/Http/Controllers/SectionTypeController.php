<?php

namespace App\Http\Controllers;

use App\Models\SectionType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionTypeController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $types = SectionType::query()
            ->where('project_id', $projectId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('section-types.index', [
            'types' => $types,
            'colorKeys' => $this->colorKeys(),
        ]);
    }

    public function store(Request $request)
    {
        $projectId = $request->user()->current_project_id;

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('section_types')->where(fn ($q) => $q->where('project_id', $projectId)),
            ],
            'color_key' => ['required', 'string', 'max:32'],
        ]);

        $data['project_id'] = $projectId;

        $max = SectionType::where('project_id', $projectId)->max('sort_order');
        $data['sort_order'] = is_null($max) ? 0 : ($max + 10);

        SectionType::create($data);

        return redirect()->route('section-types.index');
    }
    public function update(Request $request, SectionType $sectionType)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($sectionType->project_id === $projectId, 403);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('section_types')
                    ->where(fn ($q) => $q->where('project_id', $projectId))
                    ->ignore($sectionType->id),
            ],
            'color_key' => ['required', 'string', 'max:32'],
        ]);

        $sectionType->update($data);

        return redirect()->route('section-types.index');
    }

    public function destroy(Request $request, SectionType $sectionType)
    {
        $projectId = $request->user()->current_project_id;
        abort_unless($sectionType->project_id === $projectId, 403);

        $hasSections = \App\Models\Section::where('section_type_id', $sectionType->id)->exists();

        if ($hasSections) {
            return redirect()
                ->route('section-types.index')
                ->with('error', 'Cannot delete this type because sections still use it.');
        }

        $sectionType->delete();

        return redirect()->route('section-types.index');
    }


    private function colorKeys(): array
    {
        return [
            'none' => 'None',
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
