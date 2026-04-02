window.InventoryAdminLTE = (function () {
    function getMatchingElements(root, selector) {
        if (!(root instanceof Element || root instanceof Document)) {
            return [];
        }

        var elements = [];

        if (root instanceof Element && root.matches(selector)) {
            elements.push(root);
        }

        return elements.concat(Array.from(root.querySelectorAll(selector)));
    }

    function mirrorBootstrapAttributes(root) {
        getMatchingElements(root, '[data-bs-toggle]').forEach(function (element) {
            if (!element.hasAttribute('data-toggle')) {
                element.setAttribute('data-toggle', element.getAttribute('data-bs-toggle'));
            }
        });

        getMatchingElements(root, '[data-bs-target]').forEach(function (element) {
            if (!element.hasAttribute('data-target')) {
                element.setAttribute('data-target', element.getAttribute('data-bs-target'));
            }
        });

        getMatchingElements(root, '[data-bs-dismiss]').forEach(function (element) {
            if (!element.hasAttribute('data-dismiss')) {
                element.setAttribute('data-dismiss', element.getAttribute('data-bs-dismiss'));
            }
        });
    }

    function applyBootstrapClassAliases(root) {
        getMatchingElements(root, '.dropdown-menu-end').forEach(function (element) {
            if (!element.classList.contains('dropdown-menu-right')) {
                element.classList.add('dropdown-menu-right');
            }
        });
    }

    function parseTomSelectOptions(element) {
        if (!element.dataset.tomSelectOptions) {
            return {};
        }

        try {
            return JSON.parse(element.dataset.tomSelectOptions);
        } catch (error) {
            console.warn('Unable to parse Tom Select options.', error);
            return {};
        }
    }

    function initTomSelectElement(element) {
        if (!window.TomSelect || !element || element.tomselect) {
            return;
        }

        element.tomselect = new TomSelect(element, parseTomSelectOptions(element));
    }

    function initTomSelect(root) {
        getMatchingElements(root, 'select[data-tom-select]').forEach(initTomSelectElement);
    }

    function refresh(root) {
        var scope = root instanceof Element || root instanceof Document ? root : document;

        mirrorBootstrapAttributes(scope);
        applyBootstrapClassAliases(scope);
        initTomSelect(scope);
    }

    document.addEventListener('DOMContentLoaded', function () {
        refresh(document);
    });

    document.addEventListener('livewire:init', function () {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('morphed', function (_component, el) {
                refresh(el);
            });
        }
    });

    return {
        refresh: refresh,
        initTomSelectElement: initTomSelectElement,
    };
})();
