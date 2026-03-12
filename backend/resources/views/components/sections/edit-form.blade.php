@props([
    'section',
    'types' => collect(),
    'chapters' => collect(),
])

<form
    x-show="editing"
    method="POST"
    action="{{ route('sections.update', $section) }}"
    class="grid gap-4"
>
    @csrf
    @method('PATCH')

    <div>
        <label class="block text-sm font-medium mb-1">Chapter</label>
        <select
            name="chapter_id"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
        >
            <option value="">(no chapter)</option>
            @foreach($chapters as $chapter)
                <option value="{{ $chapter->id }}" @selected((string) old('chapter_id', $section->chapter_id) === (string) $chapter->id)>
                    {{ $chapter->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input
            name="title"
            value="{{ old('title', $section->title) }}"
            required
            class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
        >
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Type</label>
        <select
            name="section_type_id"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
        >
            <option value="">(no type)</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}" @selected((string) old('section_type_id', $section->section_type_id) === (string) $type->id)>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Progress</label>
        <select
            name="progress_status"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
        >
            <option value="planned" @selected(old('progress_status', $section->progress_status) === 'planned')>○ Planned</option>
            <option value="draft" @selected(old('progress_status', $section->progress_status) === 'draft')>✎ Draft</option>
            <option value="rev1" @selected(old('progress_status', $section->progress_status) === 'rev1')>① Rev 1</option>
            <option value="rev2" @selected(old('progress_status', $section->progress_status) === 'rev2')>② Rev 2</option>
            <option value="final" @selected(old('progress_status', $section->progress_status) === 'final')>✓ Final</option>
            <option value="issue" @selected(old('progress_status', $section->progress_status) === 'issue')>⚠ Issue</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Synopsis</label>
        <textarea
            name="synopsis"
            rows="4"
            class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
        >{{ old('synopsis', $section->synopsis) }}</textarea>
    </div>

    <div data-section-editor>
        <label class="block text-sm font-medium mb-1">Text</label>

        <div class="mb-2 flex items-center gap-2">
            <button type="button" data-editor-bold class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700"><strong>B</strong></button>
            <button type="button" data-editor-italic class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700"><em>I</em></button>
            <button type="button" data-editor-strike class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700"><s>S</s></button>
        </div>

        <input
            type="hidden"
            name="body"
            value="{{ old('body', $section->body) }}"
            data-editor-input
        >

        <div data-editor></div>
    </div>

    <div class="flex items-center gap-2">
        <button class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700">
            Save
        </button>

        <button
            type="button"
            @click="editing = false"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 opacity-80"
        >
            Cancel
        </button>

        <button
            type="button"
            @click.prevent="if (confirm('Delete this section?')) $refs.deleteSectionForm.submit()"
            class="px-3 py-2 rounded-xl border border-red-300 text-red-700 dark:border-red-800 dark:text-red-300"
        >
            Delete
        </button>
    </div>
</form>

<form
    x-ref="deleteSectionForm"
    method="POST"
    action="{{ route('sections.destroy', $section) }}"
    onsubmit="return confirm('Delete this section?');"
>
    @csrf
    @method('DELETE')
</form>
