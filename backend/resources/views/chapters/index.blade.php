<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chapters
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->has('chapter'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    {{ $errors->first('chapter') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('chapters.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Synopsis</label>
                        <textarea
                            name="synopsis"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        >{{ old('synopsis') }}</textarea>
                    </div>

                    <div>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md">
                            Add chapter
                        </button>
                    </div>
                </form>
            </div>

            <div
                class="bg-white shadow sm:rounded-lg p-6"
                x-data="chapterOrder(@js(route('chapters.reorder')), @js(csrf_token()))"
            >
                <h3 class="text-lg font-semibold mb-4">Reorder chapters</h3>

                <div id="chapter-list" class="space-y-3">
                    @foreach ($chapters as $chapter)
                        <div
                            class="border rounded-lg p-4 bg-gray-50 flex items-start justify-between gap-4"
                            data-id="{{ $chapter->id }}"
                        >
                            <div class="flex-1">
                                <div class="font-semibold">{{ $chapter->name }}</div>

                                @if ($chapter->synopsis)
                                    <div class="text-sm text-gray-600 mt-1 whitespace-pre-line">
                                        {{ $chapter->synopsis }}
                                    </div>
                                @endif
                            </div>

                            <div class="shrink-0 flex items-center gap-2">
                                <button type="button" class="px-2 py-1 border rounded drag-handle">↕</button>

                                <form method="POST" action="{{ route('chapters.destroy', $chapter) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="px-2 py-1 border rounded text-red-700"
                                        onclick="return confirm('Delete this chapter?')"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="button" @click="saveOrder()" class="px-4 py-2 bg-gray-900 text-white rounded-md">
                        Save order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chapterOrder(url, csrf) {
            return {
                saveOrder() {
                    const ids = [...document.querySelectorAll('#chapter-list [data-id]')]
                        .map(el => Number(el.dataset.id));

                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ chapters: ids }),
                    });
                }
            }
        }
    </script>
</x-app-layout>
