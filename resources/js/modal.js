window.LivewireUIModal = () => {
    return {
        show: false,
        showActiveComponent: true,
        activeComponent: false,
        componentHistory: [],
        modalWidth: null,
        modalFlyout: false,
        modalFlyoutPosition: 'right',
        listeners: [],

        init() {
            this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');
            this.setupEventListeners();
        },

        setupEventListeners() {
            this.listeners.push(
                Livewire.on('closeModal', (data) => {
                    this.closeModal(data?.force ?? false, data?.skipPreviousModals ?? 0, data?.destroySkipped ?? false);
                })
            );

            this.listeners.push(
                Livewire.on('activeModalComponentChanged', ({ id }) => {
                    this.setActiveModalComponent(id);
                })
            );
        },

        destroy() {
            this.listeners.forEach((listener) => {
                listener();
            });
        },

        getActiveComponentModalAttribute(key) {
            if (this.$wire.get('components')[this.activeComponent] !== undefined) {
                return this.$wire.get('components')[this.activeComponent]['modalAttributes'][key];
            }
        },

        closeModalOnEscape() {
            if (this.getActiveComponentModalAttribute('closeOnEscape') === false) {
                return;
            }

            if (!this.closingModal('closingModalOnEscape')) {
                return;
            }

            let force = this.getActiveComponentModalAttribute('closeOnEscapeIsForceful') === true;
            this.closeModal(force);
        },

        closeModalOnClickAway() {
            if (this.getActiveComponentModalAttribute('closeOnClickAway') === false) {
                return;
            }

            if (!this.closingModal('closingModalOnClickAway')) {
                return;
            }

            this.closeModal(true);
        },

        closingModal(eventName) {
            const componentName = this.$wire.get('components')[this.activeComponent].name;

            var params = {
                id: this.activeComponent,
                closing: true,
            };

            Livewire.dispatchTo(componentName, eventName, params);

            return params.closing;
        },

        closeModal(force = false, skipPreviousModals = 0, destroySkipped = false) {
            if (this.show === false) {
                return;
            }

            this.handleCloseEvents();
            this.handleSkippedModals(skipPreviousModals, destroySkipped);
            this.handleModalClosure(force);
        },

        handleCloseEvents() {
            if (this.getActiveComponentModalAttribute('dispatchCloseEvent') === true) {
                const componentName = this.$wire.get('components')[this.activeComponent].name;
                Livewire.dispatch('modalClosed', { name: componentName });
            }

            if (this.getActiveComponentModalAttribute('destroyOnClose') === true) {
                Livewire.dispatch('destroyComponent', { id: this.activeComponent });
            }
        },

        handleSkippedModals(skipPreviousModals, destroySkipped) {
            if (skipPreviousModals > 0) {
                for (var i = 0; i < skipPreviousModals; i++) {
                    if (destroySkipped) {
                        const id = this.componentHistory[this.componentHistory.length - 1];
                        Livewire.dispatch('destroyComponent', { id: id });
                    }
                    this.componentHistory.pop();
                }
            }
        },

        handleModalClosure(force) {
            const id = this.componentHistory.pop();

            if (id && !force) {
                if (id) {
                    this.setActiveModalComponent(id, true);
                } else {
                    this.setShowPropertyTo(false);
                }
            } else {
                this.setShowPropertyTo(false);
            }
        },

        setActiveModalComponent(id, skip = false) {
            this.setShowPropertyTo(true);

            if (this.activeComponent === id) {
                return;
            }

            if (this.activeComponent !== false && skip === false) {
                this.componentHistory.push(this.activeComponent);
            }

            this.updateActiveComponent(id);
        },

        updateActiveComponent(id) {
            let focusableTimeout = 50;

            if (this.activeComponent === false) {
                this.activeComponent = id
                this.showActiveComponent = true;
                this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');
                this.modalFlyout = this.getActiveComponentModalAttribute('modalFlyout');
                this.modalFlyoutPosition = this.getActiveComponentModalAttribute('modalFlyoutPosition');
            } else {
                this.showActiveComponent = false;

                focusableTimeout = 400;

                setTimeout(() => {
                    this.activeComponent = id;
                    this.showActiveComponent = true;
                    this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');
                    this.modalFlyout = this.getActiveComponentModalAttribute('modalFlyout');
                    this.modalFlyoutPosition = this.getActiveComponentModalAttribute('modalFlyoutPosition');
                }, 300);
            }

            this.setFocus(id, focusableTimeout);
        },

        setFocus(id, timeout) {
            this.$nextTick(() => {
                let focusable = this.$refs[id]?.querySelector('[autofocus]');
                if (focusable) {
                    setTimeout(() => {
                        focusable.focus();
                    }, timeout);
                }
            });
        },

        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

            return [...this.$el.querySelectorAll(selector)]
                .filter(el => !el.hasAttribute('disabled'))
        },

        firstFocusable() {
            return this.focusables()[0]
        },

        lastFocusable() {
            return this.focusables().slice(-1)[0]
        },

        nextFocusable() {
            return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable()
        },

        prevFocusable() {
            return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable()
        },

        nextFocusableIndex() {
            return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1)
        },

        prevFocusableIndex() {
            return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1
        },

        setShowPropertyTo(show) {
            this.show = show;

            if (show) {
                document.body.classList.add('overflow-y-hidden');
            } else {
                document.body.classList.remove('overflow-y-hidden');

                setTimeout(() => {
                    this.activeComponent = false;
                    this.$wire.resetState();
                }, 300);
            }
        }
    };
}
