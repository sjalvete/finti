window.mobileSectionEditor = function ({ sections, saveUrl, csrf }) {
    return {
        isOpen: false,
        sections,
        saveUrl,
        csrf,

        currentIndex: null,
        original: null,
        form: null,
        toast: null,

        activeTab: 'synopsis',
        confirming: false,
        pendingAction: null,
        isSaving: false,

        editor: null,

        initEditor() {
            if (this.editor) {
                this.editor.remove()
                this.editor = null
            }

            this.$nextTick(() => {
                const el = this.$refs.mobileEditor
                if (!el) return

                tinymce.init({
                    target: el,
                    resize: false,
                    license_key: 'gpl',
                    readonly: false,
                    skin: false,
                    content_css: false,
                    menubar: false,
                    plugins: 'link lists code table',
                    toolbar: 'undo redo | bold italic strikethrough | code',
                    tinycomments_mode: 'embedded',
                    content_style: `
                    body {
                        font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont,
                                    "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans",
                                    sans-serif;
                        font-size: 16px;
                        line-height: 1.6;
                        padding: 25px;
                    }
                    `,
                    setup: (editor) => {
                        this.editor = editor

                        editor.on('init', () => {
                            editor.setContent(this.form.body || '')
                        })

                        editor.on('change keyup', () => {
                            this.form.body = editor.getContent()
                        })
                    }
                })
            })
        },

        setActiveTab(tab) {
            this.activeTab = tab

            if (tab === 'text') {
                this.initEditor()
            }
        },

        open(sectionId) {
            const index = this.sections.findIndex(s => Number(s.id) === Number(sectionId));
            if (index === -1) return;

            this.currentIndex = index;
            this.loadCurrent();
            this.activeTab = 'synopsis';
            this.confirming = false;
            this.pendingAction = null;
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closeNow() {
            this.isOpen = false;
            this.currentIndex = null;
            this.original = null;
            this.form = null;
            this.activeTab = 'synopsis';
            this.confirming = false;
            this.pendingAction = null;
            if (this.editor) {
                this.editor.remove()
                this.editor = null
            }
            document.body.classList.remove('overflow-hidden');
        },

        currentSection() {
            if (this.currentIndex === null) return null;
            return this.sections[this.currentIndex] ?? null;
        },

        loadCurrent() {
            const section = this.currentSection();
            if (!section) return;

            this.original = JSON.parse(JSON.stringify(section));
            this.form = JSON.parse(JSON.stringify(section));

            this.$nextTick(() => {
                this.syncEditorFromForm();
            });
        },

        currentTitle() {
            return this.form?.title ?? '';
        },

        currentChapterTitle() {
            return this.form?.chapter_title ?? '';
        },

        currentTypeName() {
            return this.form?.type_name ?? '';
        },

        syncEditorFromForm() {
            if (this.editor) {
                this.editor.setContent(this.form?.body || '');
            }
        },

        isDirty() {
            return JSON.stringify(this.form) !== JSON.stringify(this.original);
        },

        hasPrev() {
            return this.currentIndex !== null && this.currentIndex > 0;
        },

        hasNext() {
            return this.currentIndex !== null && this.currentIndex < this.sections.length - 1;
        },

        requestPrev() {
            if (!this.hasPrev()) return;

            this.requestAction(() => {
                this.currentIndex--;
                this.loadCurrent();
                if (this.activeTab === 'text') {
                    this.$nextTick(() => this.syncEditorFromForm());
                }
            });
        },

        requestNext() {
            if (!this.hasNext()) return;

            this.requestAction(() => {
                this.currentIndex++;
                this.loadCurrent();
                if (this.activeTab === 'text') {
                    this.$nextTick(() => this.syncEditorFromForm());
                }
            });
        },

        requestClose() {
            this.requestAction(() => this.closeNow());
        },

        requestAction(action) {
            if (this.isDirty()) {
                this.pendingAction = action;
                this.confirming = true;
                return;
            }

            action();
        },

        async save() {
            if (!this.form) return true;

            this.isSaving = true;

            try {
                const response = await fetch(`/sections/${this.form.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    body: JSON.stringify({
                        title: this.form.title,
                        synopsis: this.form.synopsis,
                        body: this.form.body,
                        progress_status: this.form.progress_status,
                        section_type_id: this.form.section_type_id,
                        chapter_id: this.form.chapter_id,
                    }),
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('Save response:', text);
                    throw new Error('Save failed');
                }

                const updated = await response.json();

                const normalized = {
                    ...this.form,
                    ...updated.section,
                };

                this.sections[this.currentIndex] = JSON.parse(JSON.stringify(normalized));
                this.original = JSON.parse(JSON.stringify(normalized));
                this.form = JSON.parse(JSON.stringify(normalized));

                if (this.editor) {
                    this.editor.setContent(this.form.body || '');
                }

                return true;
            } catch (e) {
                console.error(e);
                alert('Could not save changes.');
                return false;
            } finally {
                this.toast = 'Saved';

                setTimeout(() => {
                    this.toast = null;
                }, 2000);
                this.isSaving = false;
            }
        },

        async saveAndContinue() {
            const ok = await this.save();
            if (!ok) return;

            this.confirming = false;

            const action = this.pendingAction;
            this.pendingAction = null;

            if (action) action();
        },

        discardAndContinue() {
            this.confirming = false;

            const action = this.pendingAction;
            this.pendingAction = null;

            if (action) action();
        },
    };
};
