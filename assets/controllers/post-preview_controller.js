import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form'];
    static values = {
        url: String
    };

    preview(event) {
        event.preventDefault();

        const form = this.hasFormTarget ? this.formTarget : this.element.closest('form');
        if (!form) {
            return;
        }

        const originalAction = form.action;
        const originalTarget = form.target;

        form.action = this.urlValue;
        form.target = '_blank';
        form.submit();

        form.action = originalAction;
        form.target = originalTarget;
    }
}
