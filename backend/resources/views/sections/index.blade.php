<x-app-layout>
    <x-slot name="header">
        @include('sections._toolbar', [
            'types' => $types,
            'progressOptions' => $progressOptions,
            'chapters' => $chapters,
        ])
    </x-slot>

    <div
        x-data="{}"
        :class="{ 'reader-mode': $store.sectionsUi.readerMode }"
        class="max-w-6xl mx-auto px-4 py-6"
    >
        <div id="chapters-list" class="grid gap-4">
            @forelse($chapters as $chapter)
                <x-sections.chapter-card
                    :chapter="$chapter"
                    :chapters="$chapters"
                    :types="$types"
                    :progress-options="$progressOptions"
                    :step-map="$stepMap"
                />
            @empty
                @include('sections._empty-state')
            @endforelse

            @if($orphaned->isNotEmpty())
                <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/40 p-4">
                    <div class="text-lg font-semibold mb-3">Unassigned</div>

                    <div class="grid gap-3">
                        @foreach($orphaned as $section)
                            <x-sections.card
                                :section="$section"
                                :types="$types"
                                :chapters="$chapters"
                                :progress-options="$progressOptions"
                                :step-map="$stepMap"
                            />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    @include('sections._create-modal', [
        'types' => $types,
        'chapters' => $chapters,
        'progressOptions' => $progressOptions,
    ])

    @include('sections._scripts')
</x-app-layout>
