import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

function smartTypographyPlugin() {
  return {
    name: 'smartTypographyPlugin',
    addInputRules() {
      return [];
    },
  };
}

function replaceTypography(text) {
  return text
    .replace(/---/g, '—')
    .replace(/\.{3}/g, '…');
}

function walkTextNodes(node) {
  const walker = document.createTreeWalker(node, NodeFilter.SHOW_TEXT);
  const nodes = [];
  let current;
  while ((current = walker.nextNode())) {
    nodes.push(current);
  }
  return nodes;
}

function applyTypographyToEditor(editor) {
  const el = document.createElement('div');
  el.innerHTML = editor.getHTML();

  for (const node of walkTextNodes(el)) {
    node.textContent = replaceTypography(node.textContent || '');
  }

  const newHtml = el.innerHTML;
  if (newHtml !== editor.getHTML()) {
    const { from, to } = editor.state.selection;
    editor.commands.setContent(newHtml, false);
    editor.commands.setTextSelection({ from, to });
  }
}

export function initSectionEditors() {
  document.querySelectorAll('[data-section-editor]').forEach((root) => {
    if (root.dataset.editorReady === '1') return;
    root.dataset.editorReady = '1';

    const editorEl = root.querySelector('[data-editor]');
    const hiddenInput = root.querySelector('[data-editor-input]');
    const boldBtn = root.querySelector('[data-editor-bold]');
    const italicBtn = root.querySelector('[data-editor-italic]');
    const strikeBtn = root.querySelector('[data-editor-strike]');

    if (!editorEl || !hiddenInput) return;

    const editor = new Editor({
      element: editorEl,
      extensions: [
        StarterKit.configure({
          heading: false,
          bulletList: false,
          orderedList: false,
          blockquote: false,
          code: false,
          codeBlock: false,
          horizontalRule: false,
        }),
      ],
      content: hiddenInput.value || '',
      editorProps: {
        attributes: {
          class:
            'min-h-[18rem] w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 focus:outline-none',
        },
      },
      onUpdate: ({ editor }) => {
        hiddenInput.value = editor.getHTML();
      },
      onCreate: ({ editor }) => {
        hiddenInput.value = editor.getHTML();
      },
    });

    const wireButton = (btn, callback) => {
      if (!btn) return;
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        callback();
        hiddenInput.value = editor.getHTML();
      });
    };

    wireButton(boldBtn, () => editor.chain().focus().toggleBold().run());
    wireButton(italicBtn, () => editor.chain().focus().toggleItalic().run());
    wireButton(strikeBtn, () => editor.chain().focus().toggleStrike().run());

    editorEl.addEventListener('blur', () => {
      applyTypographyToEditor(editor);
      hiddenInput.value = editor.getHTML();
    }, true);

    editorEl.addEventListener('keydown', (e) => {
      if (e.key === ' ' || e.key === 'Enter') {
        setTimeout(() => {
          applyTypographyToEditor(editor);
          hiddenInput.value = editor.getHTML();
        }, 0);
      }
    });

    root.closest('form')?.addEventListener('submit', () => {
      applyTypographyToEditor(editor);
      hiddenInput.value = editor.getHTML();
    });
  });
}
