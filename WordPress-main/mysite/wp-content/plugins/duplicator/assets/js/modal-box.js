/*! Duplicator iframe modal box */

class DuplicatorModalBox {
    #url;
    #modal;
    #iframe;
    #htmlContent;
    #canClose;
    #closeButton;
    #openCallack;

    constructor(options = {}) {
        if (!options.url && !options.htmlContent) {
            throw 'DuplicatorModalBox: url or htmlContent option is required';
        }

        if (options.url && options.htmlContent) {
            throw 'DuplicatorModalBox: url and htmlContent options cannot be used together';
        }

        if (options.url) {
            this.#url = options.url;
        } else {
            this.#htmlContent = options.htmlContent;
        }

        if (options.openCallback && typeof options.openCallback === 'function') {
            this.#openCallack = options.openCallback;
        } else {
            this.#openCallack = null;
        }

        this.#modal = null;
        this.#iframe = null;
        this.#canClose = true;
        this.#closeButton = null;
    }

    open() {
        // Create modal element
        this.#modal = document.createElement('div');
        this.#modal.classList.add('dup-modal-wrapper');

        // Add modal styles
        this.#addModalStyles();

        // Create close button
        this.#closeButton = document.createElement('div');
        this.#closeButton.classList.add('dup-modal-close-button');
        this.#closeButton.innerHTML = '<i class="fa-regular fa-circle-xmark"></i>';

        // Add event listener to close button
        this.#closeButton.addEventListener('click', () => {
            this.close();
        });

        // Add close button to modal
        this.#modal.appendChild(this.#closeButton);

        if (this.#url) {
            this.#insertContentAsIframe();
        } else {
            this.#insertContentAsHtml();
        }

        // Set overflow property of body to hidden
        document.body.style.overflow = 'hidden';

        // Add opacity animation
        this.#modal.animate([
            { opacity: '0' },
            { opacity: '1' }
        ], {
            duration: 500,
            iterations: 1,
        });

        // Add modal to document
        document.body.appendChild(this.#modal);
    }

    close() {
        if (!this.#canClose) {
            return;
        }

        // Remove modal from document
        document.body.removeChild(this.#modal);
        // Set overflow property of body to hidden
        document.body.style.overflow = 'auto';

        // Reset modal and iframe variables
        this.#modal = null;
        this.#iframe = null;
    }

    enableClose() {
        this.#canClose = true;
        this.#closeButton.removeAttribute('disabled');
    }

    disableClose() {
        this.#canClose = false;
        this.#closeButton.setAttribute('disabled', 'disabled');
    }

    #insertContentAsHtml() {
        let content = document.createElement('div');
        content.classList.add('dup-modal-content');
        content.innerHTML = this.#htmlContent;

        // Add content to modal
        this.#modal.appendChild(content);

        if (typeof this.#openCallack == 'function') {
            this.#openCallack(content, this);
        }
    }

    #insertContentAsIframe() {
        // Create iframe element
        this.#iframe = document.createElement('iframe');
        this.#iframe.classList.add('dup-modal-iframe');

        // Add open callback function
        if(typeof this.#openCallack == 'function') {
            let openCallack = this.#openCallack;
            let iframe = this.#iframe;
            let modalObj = this;
            this.#iframe.onload = function() {
                openCallack(iframe, modalObj);
            }; 
        }

        this.#iframe.src = this.#url;
        this.#iframe.setAttribute('frameborder', '0');
        this.#iframe.setAttribute('allowfullscreen', '');

        // Add iframe to modal
        this.#modal.appendChild(this.#iframe);
    }

    #addModalStyles() {
        const style = document.createElement('style');
        style.innerHTML = `
            .dup-modal-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(230, 230, 230, 0.9);
                z-index: 1000005;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .dup-modal-iframe {
                width: 100%;
                height: 100%;
            }

            .dup-modal-close-button {
                position: absolute;
                top: 0;
                right: 0;
                font-size: 23px;
                color: #000;
                cursor: pointer;
                line-height: 0;
                text-align: center;
                z-index: 2;
                padding: 20px;
            }

            .dup-modal-close-button[disabled] {
                opacity: 0.5;
                cursor: not-allowed;
            }
        `;
        document.head.appendChild(style);
    }
}
