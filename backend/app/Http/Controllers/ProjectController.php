<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()->orderBy('name')->get();

        return view('projects.index', [
            'projects' => $projects,
            'currentProjectId' => Auth::user()->current_project_id,
        ]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create($data);
        $request->user()->projects()->attach($project->id, ['role' => 'owner']);

        // auto-select the newly created project
        $request->user()->forceFill(['current_project_id' => $project->id])->save();

        return redirect()->route('projects.index');
    }

    public function select(Project $project, Request $request)
    {
        abort_unless($request->user()->projects()->whereKey($project->id)->exists(), 403);

        $request->user()->forceFill(['current_project_id' => $project->id])->save();

        return redirect()->route('dashboard');
    }
}
