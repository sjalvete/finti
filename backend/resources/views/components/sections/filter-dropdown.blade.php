@props([
    'types' => collect(),
    'progressOptions' => [],
])

<div class="type-filters" x-data="{ openFilter: false }">
    <div class="relative inline-block">
        <button
            type="button"
            @click="openFilter = !openFilter"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-funnel class="w-5 h-5" />
        </button>

        <div
            x-show="openFilter"
            x-cloak
            @click.outside="openFilter = false"
            class="absolute left-0 mt-2 w-72 rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg p-3 z-40"
        >
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium">Filter by progress</div>

                <div class="flex items-center gap-2 text-xs">
                    <button
                        type="button"
                        @click="$store.sectionsUi.selectedProgress = @js(array_keys($progressOptions))"
                        class="underline"
                    >
                        All
                    </button>

                    <button
                        type="button"
                        @click="$store.sectionsUi.selectedProgress = []"
                        class="underline"
                    >
                        None
                    </button>
                </div>
            </div>

            <div class="grid gap-2 max-h-72 overflow-auto">
                @foreach($progressOptions as $value => $label)
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" value="{{ $value }}" x-model="$store.sectionsUi.selectedProgress">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-4 mb-3">
                <div class="text-sm font-medium">Filter by type</div>

                <div class="flex items-center gap-2 text-xs">
                    <button
                        type="button"
                        @click="$store.sectionsUi.selectedTypes = @js(
                            collect(['none'])->merge($types->pluck('id')->map(fn($id) => (string) $id))->values()
                        )"
                        class="underline"
                    >
                        All
                    </button>

                    <button
                        type="button"
                        @click="$store.sectionsUi.selectedTypes = []"
                        class="underline"
                    >
                        None
                    </button>
                </div>
            </div>

            <div class="grid gap-2 max-h-72 overflow-auto">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" value="none" x-model="$store.sectionsUi.selectedTypes">
                    <span>No type</span>
                </label>

                @foreach($types as $type)
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" value="{{ $type->id }}" x-model="$store.sectionsUi.selectedTypes">
                        <span>{{ $type->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</div>
