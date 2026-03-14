import './bootstrap';

document.addEventListener('alpine:init', () => {
    const registerSidebarStore = (storeName, storageKey) => {
        const store = {
            open: false,
            mini: JSON.parse(localStorage.getItem(storageKey) ?? 'false'),
            isDesktop: window.innerWidth >= 1024,
            syncViewport() {
                this.isDesktop = window.innerWidth >= 1024;

                if (this.isDesktop) {
                    this.open = false;
                }
            },
            openMenu() {
                this.open = true;
            },
            closeMenu() {
                this.open = false;
            },
            toggleMini() {
                this.mini = !this.mini;
                localStorage.setItem(storageKey, JSON.stringify(this.mini));
            },
        };

        window.Alpine.store(storeName, store);
        store.syncViewport();

        window.addEventListener('resize', () => store.syncViewport());
    };

    registerSidebarStore('labSidebar', 'lab-sidebar-mini');
    registerSidebarStore('adminSidebar', 'admin-sidebar-mini');
});
