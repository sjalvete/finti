import './bootstrap';

import Alpine from 'alpinejs';

import Sortable from 'sortablejs';

import { initSectionEditors } from './section-editor';

window.Alpine = Alpine;

Alpine.store('sectionsUi', {
    createOpen: false,
    expandedAll: false,
    readerMode: false,
    reorderMode: false,
    selectedTypes: ['none'],
    selectedProgress: [ 'planned', 'draft', 'rev1', 'rev2', 'final', 'issue' ],
    matchesType(sectionTypeId) {
        return this.selectedTypes.includes(String(sectionTypeId ?? 'none'));
    },
    matchesProgress(progressStepId) {
        return this.selectedProgress.includes(String(progressStepId));
    },
});

Alpine.start();

function csrf() {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  return token || '';
}

document.addEventListener('DOMContentLoaded', () => {

  initSectionEditors();

  /*const el = document.getElementById('sections-sortable');
  if (!el) return;*/

  document.querySelectorAll('[data-sections-sortable]').forEach(container => {

    Sortable.create(container, {
      animation: 150,
      handle: '[data-drag-handle]',
      ghostClass: 'opacity-50',
      dragClass: 'opacity-80',
      onEnd: async () => {
        const ids = Array.from(container.querySelectorAll('[data-section-id]'))
          .map(x => Number(x.getAttribute('data-section-id')));

        await fetch('/sections/reorder', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf(),
            'Accept': 'application/json',
          },
          body: JSON.stringify({ ids }),
        });
      },
    });

  });

});
