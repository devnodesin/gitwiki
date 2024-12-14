import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Code Block Title Injector
 * 
 * This script enhances code blocks by dynamically adding a title element 
 * for <code> tags that have a title attribute. It provides a clean way to 
 * label code snippets with their respective filenames or descriptions.
 */
document.addEventListener("DOMContentLoaded", () => {
    // Select all <code> elements
    const codeBlocks = document.querySelectorAll("code[title]");

    codeBlocks.forEach((codeBlock) => {
        // Get the title attribute
        const title = codeBlock.getAttribute("title");

        // Create a title element
        const titleElement = document.createElement("div");
        titleElement.className = "border-dark border-bottom-0 rounded-top-1 bg-secondary text-light p-2 fs-5";
        titleElement.textContent = title;

        // Insert the title element before the code block
        codeBlock.parentNode.insertBefore(titleElement, codeBlock);
    });
});
