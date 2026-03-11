<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:18px; font-weight:600;">New project</h2>
    </x-slot>

    <div style="max-width:900px; margin:24px auto; padding:0 16px;">
        <form method="POST" action="{{ route('projects.store') }}"
              style="padding:16px; border:1px solid #ddd; border-radius:10px; display:grid; gap:12px;">
            @csrf

            <div>
                <label style="display:block; font-weight:600;">Name</label>
                <input name="name" value="{{ old('name') }}" required
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">
                @error('name') <div style="color:#b00; margin-top:6px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label style="display:block; font-weight:600;">Description</label>
                <textarea name="description" rows="4"
                          style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">{{ old('description') }}</textarea>
                @error('description') <div style="color:#b00; margin-top:6px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" style="padding:10px 14px; border:1px solid #333; border-radius:10px;">
                    Create
                </button>
                <a href="{{ route('projects.index') }}" class="underline" style="align-self:center;">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
