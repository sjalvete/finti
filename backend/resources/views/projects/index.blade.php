<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:18px; font-weight:600;">Projects</h2>
            <a href="{{ route('projects.create') }}" class="underline">New project</a>
        </div>
    </x-slot>

    <div style="max-width:900px; margin:24px auto; padding:0 16px;">
        @if($projects->isEmpty())
            <div style="padding:16px; border:1px solid #ddd; border-radius:10px;">
                No projects yet.
                <a class="underline" href="{{ route('projects.create') }}">Create one</a>.
            </div>
        @else
            <div style="display:grid; gap:12px;">
                @foreach($projects as $project)
                    <div style="padding:16px; border:1px solid #ddd; border-radius:10px; display:flex; justify-content:space-between; gap:16px;">
                        <div>
                            <div style="font-weight:600;">
                                {{ $project->name }}
                                @if($currentProjectId === $project->id)
                                    <span style="margin-left:8px; font-size:12px; padding:2px 8px; border:1px solid #999; border-radius:999px;">current</span>
                                @endif
                            </div>
                            @if($project->description)
                                <div style="margin-top:6px; opacity:.8;">{{ $project->description }}</div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('projects.select', $project) }}">
                            @csrf
                            <button type="submit" style="padding:8px 12px; border:1px solid #333; border-radius:10px;">
                                Open
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
