import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons } from 'lucide';

window.Alpine = Alpine;

Alpine.start();

const renderLucideIcons = () => {
    createIcons();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderLucideIcons, { once: true });
} else {
    renderLucideIcons();
}

let lucideScheduled = false;
const lucideObserver = new MutationObserver(() => {
    if (lucideScheduled) {
        return;
    }
    lucideScheduled = true;
    requestAnimationFrame(() => {
        renderLucideIcons();
        lucideScheduled = false;
    });
});

lucideObserver.observe(document.documentElement, {
    childList: true,
    subtree: true,
});
