<div
    x-data="mobileSectionEditor({
        sections: @js($sections->values()->map(fn($section) => [
            'id' => $section->id,
            'chapter_title' => $section->chapter?->name,
            'chapter_id' => $section->chapter?->id,
            'title' => $section->title,
            'section_type_id' => $section->section_type_id,
            'synopsis' => $section->synopsis,
            'body' => $section->body,
            'progress_status' => $section->progress_status,
        ])),
        csrf: '{{ csrf_token() }}',
    })"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 z-[100] md:hidden"
    @keydown.escape.window="requestClose()"
    @open-mobile-section-editor.window="open($event.detail.id)"
>
    <div class="absolute inset-0 bg-white flex h-dvh flex-col">

        <div class="shrink-0 border-b bg-white">
            <div class="px-4 py-3">
                <div class="text-xs text-gray-500 truncate" x-text="currentChapterTitle()"></div>
                <div class="text-sm font-semibold truncate">
                    <span x-text="currentTitle()"></span>
                    <span class="text-gray-500 font-normal">
                        (<span x-text="currentTypeName()"></span>)
                    </span>
                </div>
                <div
                    x-show="toast"
                    x-transition
                    x-text="toast"
                    class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm shadow-lg"
                ></div>
            </div>

            <div class="flex border-t">
                <button
                    type="button"
                    class="flex-1 px-4 py-3 text-sm border-b-2"
                    :class="activeTab === 'synopsis' ? 'border-primary-600 font-semibold' : 'border-transparent text-gray-500'"
                    @click="activeTab = 'synopsis'"
                >
                    Synopsis
                </button>

                <button
                    type="button"
                    class="flex-1 px-4 py-3 text-sm border-b-2"
                    :class="activeTab === 'text' ? 'border-primary-600 font-semibold' : 'border-transparent text-gray-500'"
                    @click="activeTab = 'text'; if (!editor) initEditor()"
                >
                    Text
                </button>
            </div>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto p-0">
            <template x-if="form">
                <div class="h-full">
                    <div x-show="activeTab === 'synopsis'" class="p-4 space-y-4">

                        <div>
                            <label class="block text-sm font-medium mb-1">Chapter</label>

                            <select
                                x-model="form.chapter_id"
                                class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                            >
                                <option value="">(no chapter)</option>

                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input
                                type="text"
                                x-model="form.title"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Type</label>

                            <select
                                x-model="form.section_type_id"
                                class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                            >
                                <option value="">(no type)</option>

                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Progress</label>

                            <select
                                x-model="form.progress_status"
                                class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent"
                            >
                                @foreach($progressOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Synopsis</label>
                            <textarea
                                x-model="form.synopsis"
                                rows="10"
                                class="w-full rounded-lg border-gray-300"
                            ></textarea>
                        </div>
                    </div>

                    <div x-show="activeTab === 'text'" class="m-0 flex flex-col flex-1 h-full">
                        <textarea
                            x-ref="mobileEditor"
                            class="w-full h-full"
                        ></textarea>
                    </div>
            </template>
        </div>

        <div class="shrink-0 border-t bg-white p-3">
            <div class="grid grid-cols-4 gap-2">
                <button
                    type="button"
                    class="rounded-lg border px-3 py-2 text-sm disabled:opacity-40"
                    @click="requestPrev()"
                    x-show="hasPrev()"
                >
                    Prev
                </button>
                <div x-show="!hasPrev()"></div>

                <button type="button" class="rounded-lg bg-blue-600 text-white px-3 py-2 text-sm" @click.prevent.stop="save()">Save</button>

                <button
                    type="button"
                    class="rounded-lg border px-3 py-2 text-sm"
                    @click="requestClose()"
                >
                    Close
                </button>

                <button
                    type="button"
                    class="rounded-lg border px-3 py-2 text-sm disabled:opacity-40"
                    @click="requestNext()"
                    x-show="hasNext()"
                >
                    Next
                </button>
                <div x-show="!hasNext()"></div>
            </div>
        </div>
    </div>

    <div
        x-show="confirming"
        x-cloak
        class="absolute inset-0 z-[110] bg-black/40 flex items-end"
    >
        <div class="w-full rounded-t-2xl bg-white p-4 space-y-3">
            <div class="text-sm font-semibold">Save changes?</div>
            <div class="text-sm text-gray-600">
                You have unsaved changes.
            </div>

            <div class="grid grid-cols-3 gap-2 pt-2">
                <button
                    type="button"
                    class="rounded-lg border px-3 py-2 text-sm"
                    @click="confirming = false; pendingAction = null"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="rounded-lg border px-3 py-2 text-sm"
                    @click.prevent.stop="discardAndContinue()"
                >
                    Discard
                </button>

                <button
                    type="button"
                    class="rounded-lg bg-primary-600 text-white px-3 py-2 text-sm disabled:opacity-50"
                    :disabled="isSaving"
                    @click.prevent.stop="saveAndContinue()"
                >
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
