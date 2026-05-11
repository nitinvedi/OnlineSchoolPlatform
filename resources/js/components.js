/**
 * Interactive Component Library
 * Alpine.js components for modals, dropdowns, tabs, tooltips, and more
 */

import Alpine from 'alpinejs';

// ═════════════════════════════════════════════════════════════════
// MODAL COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('modal', (initialOpen = false) => ({
    open: initialOpen,

    toggle() {
        this.open = !this.open;
        this.handleOverflow();
    },

    openModal() {
        this.open = true;
        this.handleOverflow();
    },

    closeModal() {
        this.open = false;
        this.handleOverflow();
    },

    handleOverflow() {
        if (this.open) {
            document.documentElement.style.overflow = 'hidden';
        } else {
            document.documentElement.style.overflow = 'auto';
        }
    },

    handleEscape(event) {
        if (event.key === 'Escape' && this.open) {
            this.closeModal();
        }
    },

    init() {
        document.addEventListener('keydown', (e) => this.handleEscape(e));
    },
}));

// ═════════════════════════════════════════════════════════════════
// DROPDOWN COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('dropdown', () => ({
    open: false,

    toggle() {
        this.open = !this.open;
    },

    openDropdown() {
        this.open = true;
    },

    closeDropdown() {
        this.open = false;
    },

    handleClickOutside(event) {
        if (!this.$el.contains(event.target)) {
            this.closeDropdown();
        }
    },

    selectItem(callback) {
        if (callback && typeof callback === 'function') {
            callback();
        }
        this.closeDropdown();
    },

    init() {
        document.addEventListener('click', (e) => this.handleClickOutside(e));
    },
}));

// ═════════════════════════════════════════════════════════════════
// TAB COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('tabs', (defaultActive = 0) => ({
    activeTab: defaultActive,

    setActiveTab(index) {
        this.activeTab = index;
        this.$nextTick(() => {
            // Smooth scroll to active tab if needed
            const activeTabEl = this.$refs[`tab-${index}`];
            if (activeTabEl && activeTabEl.scrollIntoView) {
                activeTabEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
            }
        });
    },

    isTabActive(index) {
        return this.activeTab === index;
    },
}));

// ═════════════════════════════════════════════════════════════════
// ACCORDION COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('accordion', (allowMultiple = false) => ({
    activeItems: [],

    toggleItem(id) {
        if (this.allowMultiple) {
            if (this.activeItems.includes(id)) {
                this.activeItems = this.activeItems.filter(item => item !== id);
            } else {
                this.activeItems.push(id);
            }
        } else {
            this.activeItems = this.activeItems.includes(id) ? [] : [id];
        }
    },

    isItemOpen(id) {
        return this.activeItems.includes(id);
    },

    openItem(id) {
        if (!this.allowMultiple) {
            this.activeItems = [id];
        } else if (!this.activeItems.includes(id)) {
            this.activeItems.push(id);
        }
    },

    closeItem(id) {
        this.activeItems = this.activeItems.filter(item => item !== id);
    },

    init() {
        this.allowMultiple = this.$el.hasAttribute('x-data') && 
                             this.$el.getAttribute('x-data').includes('true');
    },
}));

// ═════════════════════════════════════════════════════════════════
// FORM VALIDATION COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('formValidator', () => ({
    errors: {},
    touched: {},
    values: {},

    addError(field, message) {
        this.errors[field] = message;
    },

    removeError(field) {
        delete this.errors[field];
    },

    hasError(field) {
        return this.errors[field] !== undefined;
    },

    getError(field) {
        return this.errors[field] || '';
    },

    markTouched(field) {
        this.touched[field] = true;
    },

    isTouched(field) {
        return this.touched[field] === true;
    },

    shouldShowError(field) {
        return this.isTouched(field) && this.hasError(field);
    },

    validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },

    validateRequired(value) {
        return value && value.trim().length > 0;
    },

    validateMinLength(value, length) {
        return value && value.length >= length;
    },

    validateMaxLength(value, length) {
        return !value || value.length <= length;
    },

    validateMatch(value1, value2) {
        return value1 === value2;
    },

    clearErrors() {
        this.errors = {};
        this.touched = {};
    },
}));

// ═════════════════════════════════════════════════════════════════
// NOTIFICATION/TOAST COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('toast', () => ({
    visible: false,
    message: '',
    type: 'info', // 'success', 'error', 'warning', 'info'
    duration: 3000,
    timeout: null,

    show(message, type = 'info', duration = 3000) {
        this.message = message;
        this.type = type;
        this.duration = duration;
        this.visible = true;

        if (this.timeout) clearTimeout(this.timeout);

        this.timeout = setTimeout(() => {
            this.hide();
        }, duration);
    },

    hide() {
        this.visible = false;
    },

    getClasses() {
        const baseClasses = 'fixed bottom-lg right-lg z-50 px-lg py-md rounded-lg shadow-lg text-white animate-pulse-soft';
        const typeClasses = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-amber-500',
            info: 'bg-brand-500',
        };
        return `${baseClasses} ${typeClasses[this.type] || typeClasses.info}`;
    },
}));

// ═════════════════════════════════════════════════════════════════
// DISCLOSURE/DETAILS COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('disclosure', (initialOpen = false) => ({
    open: initialOpen,

    toggle() {
        this.open = !this.open;
    },

    open() {
        this.open = true;
    },

    close() {
        this.open = false;
    },
}));

// ═════════════════════════════════════════════════════════════════
// CAROUSEL/SLIDER COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('carousel', (autoplay = false, duration = 5000) => ({
    currentIndex: 0,
    autoplayInterval: null,
    autoplay,
    duration,

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.getItemsCount();
    },

    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.getItemsCount()) % this.getItemsCount();
    },

    goToSlide(index) {
        if (index >= 0 && index < this.getItemsCount()) {
            this.currentIndex = index;
        }
    },

    getItemsCount() {
        return this.$el.querySelectorAll('[x-carousel-item]').length;
    },

    isActive(index) {
        return this.currentIndex === index;
    },

    startAutoplay() {
        if (this.autoplay) {
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, this.duration);
        }
    },

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
        }
    },

    init() {
        this.startAutoplay();
        this.$el.addEventListener('mouseenter', () => this.stopAutoplay());
        this.$el.addEventListener('mouseleave', () => this.startAutoplay());
    },

    destroy() {
        this.stopAutoplay();
    },
}));

// ═════════════════════════════════════════════════════════════════
// SEARCH/FILTER COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('searchFilter', () => ({
    searchQuery: '',
    filteredResults: [],
    allResults: [],
    minChars: 2,

    setResults(results) {
        this.allResults = results;
        this.filter();
    },

    filter() {
        if (this.searchQuery.length < this.minChars) {
            this.filteredResults = this.allResults;
            return;
        }

        const query = this.searchQuery.toLowerCase();
        this.filteredResults = this.allResults.filter(item =>
            JSON.stringify(item).toLowerCase().includes(query)
        );
    },

    clear() {
        this.searchQuery = '';
        this.filter();
    },

    hasResults() {
        return this.filteredResults.length > 0;
    },

    getResultsCount() {
        return this.filteredResults.length;
    },
}));

// ═════════════════════════════════════════════════════════════════
// LOADING STATE COMPONENT
// ═════════════════════════════════════════════════════════════════
Alpine.data('loading', () => ({
    isLoading: false,
    progress: 0,

    startLoading() {
        this.isLoading = true;
        this.progress = 0;
        this.simulateProgress();
    },

    stopLoading() {
        this.isLoading = false;
        this.progress = 100;
        setTimeout(() => {
            this.progress = 0;
        }, 500);
    },

    simulateProgress() {
        if (!this.isLoading) return;

        if (this.progress < 90) {
            this.progress += Math.random() * 30;
            setTimeout(() => this.simulateProgress(), 500);
        }
    },

    setProgress(value) {
        this.progress = Math.min(value, 100);
    },
}));

export default Alpine;
