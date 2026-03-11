<div
    x-data="{}"
    x-init="$store.sectionsUi.selectedTypes = @js(
        collect(['none'])->merge($types->pluck('id')->map(fn($id) => (string) $id))->values()
    )"
    class="flex items-center justify-between gap-4"
>
    <div class="mb-4 flex items-center gap-2">
        <button
            type="button"
            @click="$store.sectionsUi.expandedAll = false"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-chevron-double-up class="w-5 h-5" />
        </button>

        <button
            type="button"
            @click="$store.sectionsUi.expandedAll = true"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-chevron-double-down class="w-5 h-5" />
        </button>

        <button
            type="button"
            @click="$store.sectionsUi.readerMode = !$store.sectionsUi.readerMode"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-eye x-show="!$store.sectionsUi.readerMode" class="w-5 h-5" />
            <x-heroicon-o-pencil x-show="$store.sectionsUi.readerMode" class="w-5 h-5" />
        </button>

        <button
            type="button"
            @click="$store.sectionsUi.reorderMode = !$store.sectionsUi.reorderMode"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-chevron-up-down class="w-5 h-5" />
        </button>

        <x-sections.filter-dropdown
            :types="$types"
            :progress-options="$progressOptions"
        />
    </div>

    <div class="flex items-center gap-3">
        <button
            type="button"
            @click="$store.sectionsUi.createOpen = true"
            class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
        >
            <x-heroicon-o-plus class="w-5 h-5" />
        </button>
    </div>
</div>
