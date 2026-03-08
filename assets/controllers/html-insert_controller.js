import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["textarea"]

    insertImage(event) {
        event.preventDefault();
        const html = '<img src="https://via.placeholder.com/800x400" alt="Image description" class="img-fluid rounded my-4">';
        this.insertAtCursor(html);
    }

    insertVideo(event) {
        event.preventDefault();
        const html = '<div class="ratio ratio-16x9 my-4"><iframe src="https://www.youtube.com/embed/XXXXX" title="YouTube video" allowfullscreen></iframe></div>';
        this.insertAtCursor(html);
    }

    insertBlockquote(event) {
        event.preventDefault();
        const html = '<blockquote class="blockquote my-4"><p>Citation ici...</p><footer class="blockquote-footer">Auteur</footer></blockquote>';
        this.insertAtCursor(html);
    }

    insertAtCursor(text) {
        const textarea = this.textareaTarget;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const value = textarea.value;

        textarea.value = value.substring(0, start) + text + value.substring(end);

        // Repositionner le curseur après l'insertion
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
        textarea.focus();

        // Déclencher un événement de changement pour d'éventuels autres contrôleurs (comme la prévisualisation)
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }
}
