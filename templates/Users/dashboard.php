<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $identity
 */
$this->setLayout('dashboard');
$this->assign('title', 'Home');
?>
<section class="card">
    <div class="card-body">
        <div class="section-header">
            <div>
                <h2 class="section-title">Welcome back, <?= h($identity->get('name') ?: $identity->get('username') ?: 'User') ?></h2>
                <p class="section-copy">Here is a quick view of what is happening in your ERMIS workspace.</p>
            </div>
        </div>

        <div class="stats-grid">
            <article class="card stat-card">
                <p class="stat-label">Total Users</p>
                <p class="stat-value">12</p>
            </article>
            <article class="card stat-card is-success">
                <p class="stat-label">Active Sessions</p>
                <p class="stat-value">8</p>
            </article>
            <article class="card stat-card is-warning">
                <p class="stat-label">Pending Actions</p>
                <p class="stat-value">3</p>
            </article>
        </div>
    </div>
</section>
