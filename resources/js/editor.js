import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

const easyMDE = new EasyMDE({
    element: document.getElementById('editor'),
    spellChecker: false,
    autosave: {
        enabled: true,
        delay: 1000,
        uniqueId: "wiki-editor"
    }
});