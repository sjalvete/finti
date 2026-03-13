@props([
    'chapter',
    'chapters' => collect(),
    'types' => collect(),
    'progressOptions' => [],
    'stepMap' => [],
])

<div
    data-chapter-id="{{ $chapter->id }}"
    x-data="{ expanded: false }"
    @click="expanded = !expanded"
    x-effect="expanded = $store.sectionsUi.expandedAll"
    class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white/60 dark:bg-gray-900/40"
>
    <div class="p-4 flex items-start justify-between gap-4">
        <div class="flex items-start gap-3 min-w-0 flex-1">
            <x-heroicon-o-chevron-right x-show="!expanded" class="w-5 h-5" />
            <x-heroicon-o-chevron-down x-show="expanded" class="w-5 h-5" />

            <div class="min-w-0 flex-1">
                <div class="text-xl font-semibold leading-tight">
                    {{ $chapter->name }}
                </div>

                <div class="mt-2 text-sm opacity-80" x-show="!expanded">
                    {{ $chapter->synopsis ?: '' }}
                </div>
            </div>
        </div>
    </div>

    <div x-show="expanded" x-cloak class="px-4 pb-4">
        <div class="grid gap-3" data-sections-sortable>
            @forelse($chapter->sections as $section)
                <x-sections.card
                    :section="$section"
                    :chapters="$chapters"
                    :types="$types"
                    :progress-options="$progressOptions"
                    :step-map="$stepMap"
                />
            @empty
                <div class="p-4 border border-gray-200 dark:border-gray-800 rounded-2xl opacity-70">
                    No sections in this chapter yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
