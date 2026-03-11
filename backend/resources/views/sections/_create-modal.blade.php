<div
    x-show="$store.sectionsUi.createOpen"
    x-data="{}"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    <div class="absolute inset-0 bg-black/50" @click="$store.sectionsUi.createOpen = false"></div>

    <div class="relative w-full max-w-2xl mx-4 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center justify-between">
            <div class="font-semibold">New section</div>
            <button
                type="button"
                @click="$store.sectionsUi.createOpen = false"
                class="px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700"
            >
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('sections.store') }}" class="mt-4 grid gap-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Chapter</label>
                <select
                    name="chapter_id"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                >
                    <option value="">(no chapter)</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}" @selected((string) old('chapter_id') === (string) $chapter->id)>
                            {{ $chapter->name }}
                        </option>
                    @endforeach
                </select>
                @error('chapter_id')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input
                    name="title"
                    value="{{ old('title') }}"
                    required
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                >
                @error('title')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Synopsis</label>
                <textarea
                    name="synopsis"
                    rows="5"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                >{{ old('synopsis') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select
                    name="section_type_id"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                >
                    <option value="">(no type)</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @selected((string) old('section_type_id') === (string) $type->id)>
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
                    <option value="planned">Planned</option>
                    <option value="draft">Draft</option>
                    <option value="rev1">Rev 1</option>
                    <option value="rev2">Rev 2</option>
                    <option value="final">Final</option>
                    <option value="issue">Issue</option>
                </select>
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
                    value=""
                    data-editor-input
                >

                <div data-editor></div>
            </div>

            <div class="flex items-center gap-2">
                <button class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700">
                    Create section
                </button>

                <button
                    type="button"
                    @click="$store.sectionsUi.createOpen = false"
                    class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 opacity-80"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
