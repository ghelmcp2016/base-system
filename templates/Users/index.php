<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
$this->setLayout('dashboard');
$this->assign('title', 'Settings - User Management');
?>
<section class="card">
    <div class="card-body">
        <div class="section-header">
            <div>
                <h2 class="section-title">User Management</h2>
                <p class="section-copy">Manage access, update user profiles, and keep the workspace organized.</p>
            </div>
            <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">Add User</a>
        </div>

        <?php if (!empty($users)): ?>
            <div class="table-wrap">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="avatar">
                                            <?= strtoupper(substr($user->name ?: $user->username ?: 'U', 0, 1)) ?>
                                        </div>
                                        <div class="user-meta">
                                            <span class="user-name"><?= h($user->name ?: $user->username ?: 'User') ?></span>
                                            <span class="user-email"><?= h($user->username ?: '') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= h($user->email) ?></td>
                                <td><?= $user->created ? $user->created->format('M d, Y') : '-' ?></td>
                                <td><span class="status-pill">Active</span></td>
                                <td>
                                    <div class="actions-row">
                                        <a href="<?= $this->Url->build(['action' => 'edit', $user->id]) ?>" class="btn btn-secondary">Edit</a>
                                        <?= $this->Form->postLink(
                                            'Delete',
                                            ['action' => 'delete', $user->id],
                                            ['class' => 'btn btn-danger', 'confirm' => 'Are you sure?']
                                        ) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3 class="empty-state-title">No users yet</h3>
                <p class="empty-state-text">Create your first user to get started.</p>
                <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary">Add First User</a>
            </div>
        <?php endif; ?>
    </div>
</section>
