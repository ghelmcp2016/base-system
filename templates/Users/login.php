<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Log in');
$this->assign('bodyClass', 'auth-page login-page');
?>
<div class="auth-card">
    <div class="auth-header">
        <span class="auth-eyebrow">ERMIS Access</span>
        <h1>Welcome back</h1>
        <p>Sign in to continue to your workspace.</p>
    </div>

    <?= $this->Form->create(null, ['url' => '/login']) ?>
    <div class="form-grid">
        <?= $this->Form->control('email', [
            'type' => 'email',
            'label' => 'Email',
            'placeholder' => 'email@example.com',
            'required' => true,
        ]) ?>

        <?= $this->Form->control('password', [
            'type' => 'password',
            'label' => 'Password',
            'placeholder' => 'Your password',
            'required' => true,
        ]) ?>

        <div class="aux-link-row">
            <a href="#">Forgot password?</a>
        </div>

        <?= $this->Form->button('Log in', ['class' => 'btn btn-primary btn-block']) ?>
    </div>
    <?= $this->Form->end() ?>

    <div class="page-link-row">
        First time here?
        <a href="<?= $this->Url->build(['action' => 'register']) ?>">Create an account</a>
    </div>
</div>
