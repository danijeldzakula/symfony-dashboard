/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

/**
 * Toggle class for all toggled element
 * @param {string} selector - (select wrapper of element and toggling)
 * @param {string} toggle - (toggle trigger for this element)
 */
class Toggle {
    // constructor class
    constructor(selector, toggle) {
        this.selector = document.querySelectorAll(selector);
        this.toggle = document.querySelectorAll(toggle);
    }
    // init method
    init() {
        this.showElement();
        this.hideElement();
        this.closeElement();
        this.hideEscapeElement();
    }
    // show overlay on click - selector
    toggledElement(id) {
        if (!id) {
            return;
        }
        for (let element of this.toggle) {
            let targetId = element.dataset.toggleSelector;
            if (targetId === id) element.classList.toggle('is-show');
        }
    }
    // button - toggle
    showElement() {
        for (let element of this.selector) {
            element.addEventListener('click', (event) => {
                let targetId = event.target.dataset.toggle;
                this.toggledElement(targetId);
            }, false);
        }
    }
    // close toggle - element
    closeElement() {
        for (let element of this.toggle) {
            element.addEventListener('click', (event) => {
                let target = event.target?.closest('[data-toggle-close]');
                if (target) {
                    element.classList.remove('is-show');

                }
            }, false);
        }        
    }
    // hide element on click - overlay
    hideElement() {
        for (let element of this.toggle) {
            element.addEventListener('click', (event) => {
                let targetId = event.target.dataset.toggleSelector;
                this.toggledElement(targetId);
            }, false);
        }
    }
    // hide on escape event
    hideEscapeElement() {
        window.addEventListener('keydown', (event) => {
            if(event.key === 'Escape' || event.key === 'Esc') {
                for (let element of this.toggle) {
                    element.classList.remove('is-show');
                }
            }  
        }, false);
    }
}

let toggleModal = new Toggle('.toggle-modal', '.modal-overlay').init();
let toggleSidebar = new Toggle('.toggle-sidebar', '.sidebar-overlay').init();


// show hidden password
class HiddenPassword {
    // constructor class
    constructor(toggle) {
        this.toggle = document.querySelectorAll(toggle);
    }    

    // init method
    init() {
        this.toggledElement();
    }

    toggledElement() {
        for (let element of this.toggle) {
            element.addEventListener('click', (event) => {
                let buttonTarget = event.target?.closest('[data-toggle="true"]');
                let inputTarget = element.querySelector('[data-show-password]');
                
                if (buttonTarget) {
                    // toggle show element
                    element.classList.toggle('is-visible');
                    // toggle type of input 
                    if (inputTarget.type === 'password') {
                        inputTarget.type = 'text';
                    } else {
                        inputTarget.type = 'password';
                    }
                }

            }, false);
        }
    }
}

let toggleHiddenPassword = new HiddenPassword('.hidden-password').init();