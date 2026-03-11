<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold">Section types</h2>
        </div>
    </x-slot>
    @if(session('error'))
        <div class="p-3 rounded-xl border border-red-300 text-red-700 dark:border-red-800 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    <div class="max-w-5xl mx-auto px-4 py-6 grid gap-6">
        <form method="POST" action="{{ route('section-types.store') }}"
              class="p-4 border border-gray-200 dark:border-gray-800 rounded-2xl grid gap-4">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Type title</label>
                    <input
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                    >
                    @error('name')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Colour</label>
                    <select
                        name="color_key"
                        required
                        class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                    >
                        @foreach($colorKeys as $key => $label)
                            <option value="{{ $key }}" @selected(old('color_key') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('color_key')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <button class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700">
                    Create type
                </button>
            </div>
        </form>

        <div class="grid gap-3">
            @forelse($types as $type)
                <div
                    x-data="{ editOpen:false }"
                    class="p-4 border border-gray-200 dark:border-gray-800 rounded-2xl type-bg type-{{ $type->color_key }}"
                >
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="font-semibold">{{ $type->name }}</div>
                            <div class="text-sm opacity-70">{{ $type->color_key }}</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="editOpen=true"
                                class="px-3 py-2 rounded-xl border border-black/10 dark:border-white/10 bg-white/60 dark:bg-black/20"
                                title="Edit section type"
                            >
                                ✎
                            </button>

                            <form method="POST" action="{{ route('section-types.destroy', $type) }}"
                                onsubmit="return confirm('Delete this section type?');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="px-3 py-2 rounded-xl border border-red-300 text-red-700 dark:border-red-800 dark:text-red-300 bg-white/60 dark:bg-black/20"
                                    title="Delete section type"
                                >
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>

                    <div
                        x-show="editOpen"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center"
                    >
                        <div class="absolute inset-0 bg-black/50" @click="editOpen=false"></div>

                        <div class="relative w-full max-w-lg mx-4 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold">Edit section type</div>
                                <button
                                    type="button"
                                    @click="editOpen=false"
                                    class="px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700"
                                >
                                    ✕
                                </button>
                            </div>

                            <form method="POST" action="{{ route('section-types.update', $type) }}" class="mt-4 grid gap-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-sm font-medium mb-1">Type title</label>
                                    <input
                                        name="name"
                                        value="{{ old('name', $type->name) }}"
                                        required
                                        class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                                    >
                                    @error('name')
                                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Colour</label>
                                    <select
                                        name="color_key"
                                        required
                                        class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                                    >
                                        @foreach($colorKeys as $key => $label)
                                            <option value="{{ $key }}" @selected(old('color_key', $type->color_key) === $key)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('color_key')
                                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-2 mt-2">
                                    <button class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700">
                                        Save
                                    </button>
                                    <button
                                        type="button"
                                        @click="editOpen=false"
                                        class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 opacity-80"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 border border-gray-200 dark:border-gray-800 rounded-2xl">
                    No section types yet.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
