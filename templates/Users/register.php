<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Sign up');
$this->assign('bodyClass', 'auth-page register-page');
?>
<div class="auth-card">
    <div class="auth-header">
        <span class="auth-eyebrow">ERMIS Access</span>
        <h1>Create account</h1>
        <p>Set up your access and start using the system.</p>
    </div>

    <?= $this->Form->create($user) ?>
    <div class="form-grid">
        <?= $this->Form->control('first_name', [
            'type' => 'text',
            'label' => 'First name',
            'placeholder' => 'John',
            'required' => true,
        ]) ?>

        <?= $this->Form->control('last_name', [
            'type' => 'text',
            'label' => 'Last name',
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

        <?= $this->Form->button('Create account', ['class' => 'btn btn-primary btn-block']) ?>
    </div>
    <?= $this->Form->end() ?>

    <div class="page-link-row">
        Already have an account?
        <a href="<?= $this->Url->build(['action' => 'login']) ?>">Log in</a>
    </div>
</div>
