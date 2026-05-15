<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->setLayout('dashboard');
$this->assign('title', 'Add User');
?>
<section class="card form-card">
    <div class="card-body">
        <div class="section-header">
            <div>
                <h2 class="section-title">Add New User</h2>
                <p class="section-copy">Create a new account and grant access to the workspace.</p>
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
                'label' => 'Password',
                'placeholder' => 'Create a strong password',
                'required' => true,
            ]) ?>
        </div>

        <div class="form-actions">
            <?= $this->Form->button('Create User', ['class' => 'btn btn-primary']) ?>
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-secondary">Cancel</a>
        </div>
        <?= $this->Form->end() ?>
    </div>
</section>
