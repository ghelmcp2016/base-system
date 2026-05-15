<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->setLayout('dashboard');
$this->assign('title', 'Edit User');
?>
<section class="card form-card">
    <div class="card-body">
        <div class="section-header">
            <div>
                <h2 class="section-title">Edit User</h2>
                <p class="section-copy">Update account details or remove access if this user is no longer active.</p>
            </div>
        </div>

        <?= $this->Form->create($user) ?>
        <div class="form-grid">
            <?= $this->Form->control('first_name', [
                'type' => 'text',
                'label' => 'First Name',
                'placeholder' => 'John',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('last_name', [
                'type' => 'text',
                'label' => 'Last Name',
                'placeholder' => 'Doe',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('username', [
                'type' => 'text',
                'label' => 'Username',
                'placeholder' => 'john.doe',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('email', [
                'type' => 'email',
                'label' => 'Email',
                'placeholder' => 'email@example.com',
                'required' => true,
            ]) ?>

            <?= $this->Form->control('password', [
                'type' => 'password',
                'label' => 'Password (leave blank to keep current)',
                'placeholder' => 'Enter new password or leave blank',
                'required' => false,
            ]) ?>
        </div>

        <div class="form-actions">
            <?= $this->Form->button('Update User', ['class' => 'btn btn-primary']) ?>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-secondary">Cancel</a>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $user->id],
                ['method' => 'delete', 'class' => 'btn btn-danger', 'confirm' => 'Are you sure?']
            ) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</section>
