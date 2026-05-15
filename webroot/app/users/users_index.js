(() => {
    const root = document.getElementById('users-app');
    if (!root || typeof Vue === 'undefined') {
        return;
    }

    const { createApp } = Vue;
    const endpoint = root.dataset.usersEndpoint;

    const emptyForm = () => ({
        id: null,
        first_name: '',
        last_name: '',
        username: '',
        email: '',
        password: '',
        status: 'active',
        role: 'user',
    });

    createApp({
        data() {
            return {
                users: [],
                loading: false,
                tableBusy: false,
                saving: false,
                search: '',
                pageSize: 5,
                pageSizeOptions: [5, 10, 20, 50],
                currentPage: 1,
                tableBusyTimer: null,
                formMode: 'create',
                activeModal: null,
                deleteCandidate: null,
                form: emptyForm(),
                errors: {},
                flash: {
                    type: '',
                    message: '',
                },
            };
        },
        computed: {
            filteredUsers() {
                const needle = this.search.toLowerCase();
                if (!needle) {
                    return this.users;
                }

                return this.users.filter((user) => {
                    return [
                        user.name,
                        user.username,
                        user.email,
                        user.first_name,
                        user.last_name,
                    ]
                        .filter(Boolean)
                        .some((value) => String(value).toLowerCase().includes(needle));
                });
            },
            totalPages() {
                return Math.max(1, Math.ceil(this.filteredUsers.length / this.pageSize));
            },
            paginatedUsers() {
                const start = (this.currentPage - 1) * this.pageSize;
                return this.filteredUsers.slice(start, start + this.pageSize);
            },
            pageRangeStart() {
                if (!this.filteredUsers.length) {
                    return 0;
                }

                return (this.currentPage - 1) * this.pageSize + 1;
            },
            pageRangeEnd() {
                if (!this.filteredUsers.length) {
                    return 0;
                }

                return Math.min(this.currentPage * this.pageSize, this.filteredUsers.length);
            },
        },
        watch: {
            search() {
                this.currentPage = 1;
                this.pulseTableBusy();
            },
            pageSize() {
                this.currentPage = 1;
                this.pulseTableBusy();
            },
            filteredUsers() {
                if (this.currentPage > this.totalPages) {
                    this.currentPage = this.totalPages;
                }
            },
        },
        mounted() {
            this.loadUsers();
            document.addEventListener('keydown', this.handleEscape);
        },
        beforeUnmount() {
            document.removeEventListener('keydown', this.handleEscape);
            if (this.tableBusyTimer) {
                clearTimeout(this.tableBusyTimer);
            }
        },
        methods: {
            handleEscape(event) {
                if (event.key === 'Escape' && !this.saving) {
                    this.closeModal();
                }
            },
            pulseTableBusy(duration = 180) {
                this.tableBusy = true;

                if (this.tableBusyTimer) {
                    clearTimeout(this.tableBusyTimer);
                }

                this.tableBusyTimer = window.setTimeout(() => {
                    this.tableBusy = false;
                    this.tableBusyTimer = null;
                }, duration);
            },
            async loadUsers() {
                this.loading = true;
                this.tableBusy = true;
                try {
                    const response = await fetch(endpoint, {
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    const payload = await response.json();
                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Unable to load users.');
                    }

                    this.users = payload.data;
                    if (this.currentPage > this.totalPages) {
                        this.currentPage = this.totalPages;
                    }
                } catch (error) {
                    this.showFlash('error', error.message || 'Unable to load users.');
                } finally {
                    this.loading = false;
                    this.pulseTableBusy(500);
                }
            },
            openCreate() {
                this.formMode = 'create';
                this.form = emptyForm();
                this.errors = {};
                this.activeModal = 'form';
            },
            openEdit(user) {
                this.formMode = 'edit';
                this.errors = {};
                this.form = {
                    id: user.id,
                    first_name: user.first_name || '',
                    last_name: user.last_name || '',
                    username: user.username || '',
                    email: user.email || '',
                    password: '',
                    status: user.status || 'active',
                    role: user.role || 'user',
                };
                this.activeModal = 'form';
            },
            resetForm() {
                this.form = emptyForm();
                this.errors = {};
                this.formMode = 'create';
            },
            closeModal(force = false) {
                if (this.saving && !force) {
                    return;
                }

                this.activeModal = null;
                this.deleteCandidate = null;
                this.resetForm();
            },
            openDelete(user) {
                this.deleteCandidate = user;
                this.activeModal = 'delete';
            },
            fieldError(field) {
                const value = this.errors[field];
                if (!value) {
                    return '';
                }

                return Array.isArray(value) ? value[0] : value;
            },
            showFlash(type, message) {
                this.flash = { type, message };
            },
            goToPreviousPage() {
                if (this.currentPage > 1) {
                    this.pulseTableBusy();
                    this.currentPage -= 1;
                }
            },
            goToNextPage() {
                if (this.currentPage < this.totalPages) {
                    this.pulseTableBusy();
                    this.currentPage += 1;
                }
            },
            statusClass(status) {
                return {
                    'status-pill-inactive': status === 'inactive',
                    'status-pill-suspended': status === 'suspended',
                };
            },
            initialFor(user) {
                const source = user.name || user.username || 'U';
                return source.charAt(0).toUpperCase();
            },
            async submitForm() {
                this.saving = true;
                this.errors = {};

                const isEdit = this.formMode === 'edit' && this.form.id;
                const url = isEdit ? `${endpoint}/${this.form.id}/edit` : `${endpoint}/add`;

                const payload = {
                    first_name: this.form.first_name,
                    last_name: this.form.last_name,
                    username: this.form.username,
                    email: this.form.email,
                    password: this.form.password,
                    status: this.form.status,
                    role: this.form.role,
                };

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        this.errors = data.errors || {};
                        throw new Error(data.message || 'Unable to save user.');
                    }

                    this.showFlash('success', data.message || 'User saved successfully.');
                    await this.loadUsers();
                    this.closeModal(true);
                } catch (error) {
                    this.showFlash('error', error.message || 'Unable to save user.');
                } finally {
                    this.saving = false;
                }
            },
            async confirmDelete() {
                if (!this.deleteCandidate) {
                    return;
                }

                this.saving = true;
                try {
                    const response = await fetch(`${endpoint}/${this.deleteCandidate.id}/delete`, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Unable to delete user.');
                    }

                    this.showFlash('success', data.message || 'User deleted successfully.');
                    await this.loadUsers();
                    if (this.form.id === this.deleteCandidate.id) {
                        this.resetForm();
                    }
                    this.closeModal(true);
                } catch (error) {
                    this.showFlash('error', error.message || 'Unable to delete user.');
                } finally {
                    this.saving = false;
                }
            },
        },
    }).mount('#users-app');
})();
