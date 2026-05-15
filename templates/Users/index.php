<?php
/**
 * @var \App\View\AppView $this
 */
$this->setLayout('dashboard');
$this->assign('title', 'Settings - User Management');
$csrfToken = (string)($this->request->getCookie('csrfToken') ?? $this->request->getAttribute('csrfToken'));
?>
<section class="card">
    <style>[v-cloak]{display:none;}</style>
    <div
        id="users-app"
        class="card-body"
        v-cloak
        data-csrf-token="<?= h($csrfToken) ?>"
        data-users-endpoint="<?= h($this->Url->build('/api/users')) ?>"
    >
        <div class="section-header">
            <div>
                <h2 class="section-title">User Management</h2>
                <p class="section-copy">Manage users from a Vue frontend backed by CakePHP API endpoints.</p>
            </div>
            <button type="button" class="btn btn-primary" @click="openCreate">
                Add User
            </button>
        </div>

        <div v-if="flash.message" class="message" :class="flash.type">
            {{ flash.message }}
        </div>

        <div class="vue-content">
            <div class="vue-toolbar">
                <div class="vue-search">
                    <input v-model.trim="search" type="search" placeholder="Search name, username, or email">
                </div>
                <div class="toolbar-actions">
                    <label class="rows-select">
                        <span>Show</span>
                        <select v-model.number="pageSize">
                            <option v-for="size in pageSizeOptions" :key="size" :value="size">
                                {{ size }}
                            </option>
                        </select>
                        <span>rows</span>
                    </label>
                    <button type="button" class="btn btn-secondary" @click="loadUsers" :disabled="loading">
                        {{ loading ? 'Refreshing...' : 'Refresh' }}
                    </button>
                </div>
            </div>

            <div v-if="loading" class="empty-state">
                <h3 class="empty-state-title">Loading users</h3>
                <p class="empty-state-text">Fetching records from the API endpoint.</p>
            </div>

            <div v-else-if="filteredUsers.length" class="table-panel" :class="{ 'is-busy': tableBusy }">
                <div v-if="tableBusy" class="table-loading-overlay" aria-hidden="true">
                    <div class="table-loading-indicator">
                        <span class="table-loading-spinner"></span>
                        <span>Updating table...</span>
                    </div>
                </div>

                <div class="table-wrap">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in paginatedUsers" :key="user.id">
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">
                                        {{ initialFor(user) }}
                                    </div>
                                    <div class="user-meta">
                                        <span class="user-name">{{ user.name || user.username }}</span>
                                        <span class="user-email">{{ user.username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span class="status-pill" :class="statusClass(user.status)">
                                    {{ user.status }}
                                </span>
                            </td>
                            <td>{{ user.role }}</td>
                            <td>{{ user.created || '-' }}</td>
                            <td>
                                <div class="actions-row">
                                    <button type="button" class="btn btn-secondary" @click="openEdit(user)">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" @click="openDelete(user)" :disabled="saving">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>

                <div class="table-footer">
                    <p class="table-results">
                        Showing {{ pageRangeStart }} to {{ pageRangeEnd }} of {{ filteredUsers.length }} results
                    </p>
                    <div class="pagination-controls">
                        <button
                            type="button"
                            class="pagination-btn"
                            @click="goToPreviousPage"
                            :disabled="currentPage === 1"
                            aria-label="Previous page"
                        >
                            &#8249;
                        </button>
                        <span class="pagination-page">Page {{ currentPage }} of {{ totalPages }}</span>
                        <button
                            type="button"
                            class="pagination-btn"
                            @click="goToNextPage"
                            :disabled="currentPage === totalPages"
                            aria-label="Next page"
                        >
                            &#8250;
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="empty-state">
                <h3 class="empty-state-title">No matching users</h3>
                <p class="empty-state-text">Try a different search or create a new user.</p>
            </div>
        </div>

        <div
            v-if="activeModal === 'form'"
            class="modal-backdrop"
            @click.self="closeModal"
        >
            <div class="modal-card" role="dialog" aria-modal="true" :aria-labelledby="formMode === 'create' ? 'create-user-title' : 'edit-user-title'">
                <div class="modal-header">
                    <div>
                        <h3 :id="formMode === 'create' ? 'create-user-title' : 'edit-user-title'" class="modal-title">
                            {{ formMode === 'create' ? 'Create user' : 'Update user' }}
                        </h3>
                        <p class="modal-copy">
                            {{ formMode === 'create' ? 'Add a new user through the API.' : 'Make changes to this user and save them through the API.' }}
                        </p>
                    </div>
                    <button type="button" class="modal-close" @click="closeModal" :disabled="saving" aria-label="Close">
                        &times;
                    </button>
                </div>

                <form class="form-grid" @submit.prevent="submitForm">
                    <div class="form-field">
                        <label for="first-name">First Name</label>
                        <input id="first-name" v-model.trim="form.first_name" type="text" placeholder="John">
                        <div v-if="fieldError('first_name')" class="form-error">{{ fieldError('first_name') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="last-name">Last Name</label>
                        <input id="last-name" v-model.trim="form.last_name" type="text" placeholder="Doe">
                        <div v-if="fieldError('last_name')" class="form-error">{{ fieldError('last_name') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="username">Username</label>
                        <input id="username" v-model.trim="form.username" type="text" placeholder="john.doe">
                        <div v-if="fieldError('username')" class="form-error">{{ fieldError('username') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" v-model.trim="form.email" type="email" placeholder="email@example.com">
                        <div v-if="fieldError('email')" class="form-error">{{ fieldError('email') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="password">
                            {{ formMode === 'create' ? 'Password' : 'Password (leave blank to keep current)' }}
                        </label>
                        <input id="password" v-model="form.password" type="password" :placeholder="formMode === 'create' ? 'Create a password' : 'Leave blank to keep current password'">
                        <div v-if="fieldError('password')" class="form-error">{{ fieldError('password') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="status">Status</label>
                        <select id="status" v-model="form.status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                        <div v-if="fieldError('status')" class="form-error">{{ fieldError('status') }}</div>
                    </div>

                    <div class="form-field">
                        <label for="role">Role</label>
                        <select id="role" v-model="form.role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                        </select>
                        <div v-if="fieldError('role')" class="form-error">{{ fieldError('role') }}</div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" @click="closeModal" :disabled="saving">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="saving">
                            {{ saving ? 'Saving...' : formMode === 'create' ? 'Create User' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div
            v-if="activeModal === 'delete' && deleteCandidate"
            class="modal-backdrop"
            @click.self="closeModal"
        >
            <div class="modal-card modal-card-sm" role="dialog" aria-modal="true" aria-labelledby="delete-user-title">
                <div class="modal-header">
                    <div>
                        <h3 id="delete-user-title" class="modal-title">Delete user</h3>
                        <p class="modal-copy">This action cannot be undone.</p>
                    </div>
                    <button type="button" class="modal-close" @click="closeModal" :disabled="saving" aria-label="Close">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <p class="modal-text">
                        Are you sure you want to delete <strong>{{ deleteCandidate.name || deleteCandidate.username }}</strong>?
                    </p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" @click="closeModal" :disabled="saving">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete" :disabled="saving">
                        {{ saving ? 'Deleting...' : 'Delete User' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->start('scriptBottom'); ?>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="<?= $this->Url->assetUrl('app/users/users_index.js') ?>"></script>

<?php $this->end(); ?>
