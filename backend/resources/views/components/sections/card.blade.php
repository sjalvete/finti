@props([
    'section',
    'types' => collect(),
    'chapters' => collect(),
    'progressOptions' => [],
    'stepMap' => [],
])

@php

    $colorKey = $section->type?->color_key ?? 'none';
    $bgClass = 'type-bg type-' . $colorKey;
@endphp

<div
    data-section-id="{{ $section->id }}"
    x-show="
        $store.sectionsUi.matchesType('{{ $section->section_type_id ?? 'none' }}')
        && $store.sectionsUi.matchesProgress('{{ $section->progress_status }}')
    "
    x-data="{ expanded: false, editing: false }"
    x-effect="
        expanded = $store.sectionsUi.expandedAll;
        if ($store.sectionsUi.readerMode) editing = false;
    "
    class="relative p-4 border border-gray-200 dark:border-gray-800 rounded-2xl {{ $bgClass }}"
    @click="
        if (window.innerWidth < 768 && $store.sectionsUi.readerMode === false) {
            $dispatch('open-mobile-section-editor', { id: {{ $section->id }} })
        }
    "
>

    <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-3 min-w-0 flex-1">
            <button @click="expanded = !expanded" class="px-3 py-2">
                <x-heroicon-o-chevron-right x-show="!expanded" class="w-5 h-5" />
                <x-heroicon-o-chevron-down x-show="expanded" class="w-5 h-5" />
            </button>

            <div class="w-full flex-1">
                <div class="text-xl font-semibold leading-tight flex items-center justify-between md:justify-start gap-4">
                    {{ $section->title }}
                    <x-progress-stepper :step="$stepMap[$section->progress_status] ?? 0" />
                </div>

                <div class="mt-2 text-sm opacity-80" x-show="!expanded">
                    {{ $section->synopsis }}
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            @if($section->type)
                <span class="section-type-pill text-xs px-3 py-1">
                    {{ $section->type->name }}
                </span>
            @endif

            <button
                type="button"
                data-drag-handle
                class="hidden md:inline-flex section-drag-handle mt-1 px-2 py-1 cursor-grab active:cursor-grabbing"
                x-show="$store.sectionsUi.reorderMode"
                title="Drag to reorder"
                @click.stop
                @pointerdown.stop
            >
                <x-heroicon-o-chevron-up-down class="w-5 h-5" />
            </button>
        </div>
    </div>

    <div x-show="expanded" x-cloak class="mt-4">
        <div x-show="!editing">
            @if($section->body)
                <div class="prose prose-sm max-w-none dark:prose-invert leading-7">
                    {!! $section->body !!}
                </div>
            @else
                <div class="opacity-60 italic">No text yet.</div>
            @endif

            <div class="mt-4 section-edit-controls">
                <button
                    type="button"
                    @click="editing = true"
                    class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700"
                >
                    Edit
                </button>
            </div>
        </div>

        <x-sections.edit-form
            :section="$section"
            :types="$types"
            :chapters="$chapters"
        />
    </div>
</div>
