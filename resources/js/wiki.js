import 'mermaid';
import hljs from 'highlight.js';

// first, find all the div.code blocks
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('code.code').forEach((el) => {
        hljs.highlightElement(el);
    });
});