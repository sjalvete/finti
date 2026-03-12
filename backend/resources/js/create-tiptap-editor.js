import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
// import other extensions you already use
// import Placeholder from '@tiptap/extension-placeholder'

export function createTiptapEditor({ element, content = '', onUpdate }) {
    return new Editor({
        element,
        extensions: [
            StarterKit,
            // ...same extensions as desktop
            // Placeholder.configure({ placeholder: 'Write here...' }),
        ],
        content,
        onUpdate: ({ editor }) => {
            onUpdate?.(editor.getHTML(), editor)
        },
    })
}
