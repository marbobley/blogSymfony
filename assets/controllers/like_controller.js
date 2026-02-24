import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['count', 'icon'];
    static values = {
        url: String,
        liked: Boolean
    };

    async toggle(event) {
        event.preventDefault();

        if (this.isProcessing) {
            return;
        }

        this.isProcessing = true;
        this.element.classList.add('disabled');

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                this.likedValue = !this.likedValue;
                this.updateUI();
            }
        } catch (error) {
            console.error('An error occurred during toggle like', error);
        } finally {
            this.isProcessing = false;
            this.element.classList.remove('disabled');
        }
    }

    updateUI() {
        let count = parseInt(this.countTarget.textContent);
        count = this.likedValue ? count + 1 : count - 1;
        this.countTarget.textContent = count;

        if (this.likedValue) {
            this.element.classList.replace('btn-outline-primary', 'btn-primary');
            this.iconTarget.classList.replace('bi-heart', 'bi-heart-fill');
        } else {
            this.element.classList.replace('btn-primary', 'btn-outline-primary');
            this.iconTarget.classList.replace('bi-heart-fill', 'bi-heart');
        }
    }
}
