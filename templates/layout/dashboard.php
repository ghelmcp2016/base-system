<?php
/**
 * @var \App\View\AppView $this
 */
$pageTitle = trim((string)$this->fetch('title'));
$fullTitle = $pageTitle !== '' ? $pageTitle . ' | ERMIS' : 'ERMIS';
$currentAction = (string)$this->request->getParam('action');
$identity = $this->request->getAttribute('identity');
$displayName = trim((string)($identity?->get('name') ?? ''));
$initial = strtoupper(substr($displayName !== '' ? $displayName : 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<!-- Header -->
<?= $this->element('layout/head', ['title' => $fullTitle]) ?>
<body>
    <!-- Body -->
    <div class="app-shell dashboard-layout">
        <aside class="dashboard-sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-mark">ER</div>
                <div>
                    <p class="sidebar-brand-name">ERMIS</p>
                    <p class="sidebar-brand-meta">Operations Workspace</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-nav-group">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']) ?>" class="sidebar-link<?= $currentAction === 'dashboard' ? ' is-active' : '' ?>">
                        <span class="sidebar-icon">HM</span>
                        <span>Home</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">IN</span>
                        <span>Inbox</span>
                        <span class="sidebar-link-badge">12</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">DC</span>
                        <span>Documents</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">CL</span>
                        <span>Calendar</span>
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-title">Management</p>
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>" class="sidebar-link<?= in_array($currentAction, ['index', 'add', 'edit', 'view'], true) ? ' is-active' : '' ?>">
                        <span class="sidebar-icon">US</span>
                        <span>Users</span>
                    </a>
                </div>

                <div class="sidebar-nav-group">
                    <p class="sidebar-nav-title">Favorites</p>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">MK</span>
                        <span>Marketing site</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">AN</span>
                        <span>Android app</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <span class="sidebar-icon">BG</span>
                        <span>Brand guidelines</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-head">
                        <div class="avatar"><?= h($initial) ?></div>
                        <div>
                            <p class="sidebar-user-name"><?= h($displayName !== '' ? $displayName : 'User') ?></p>
                            <p class="sidebar-user-email"><?= h($identity?->get('email') ?? '') ?></p>
                        </div>
                    </div>
                    <?= $this->Form->create(null, [
                        'url' => ['controller' => 'Users', 'action' => 'logout'],
                    ]) ?>
                        <button type="submit" class="btn btn-danger btn-block">Logout</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <main class="dashboard-main">
            <header class="dashboard-header">
                <div class="dashboard-header-card">
                    <h1 class="dashboard-title"><?= h($pageTitle !== '' ? $pageTitle : 'Dashboard') ?></h1>
                    <p class="dashboard-subtitle">A centralized ERMIS workspace without the default CakePHP starter design.</p>
                </div>
            </header>

            <section class="dashboard-content stack-md">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </section>
        </main>
    </div>
    <!-- Scripts -->
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
