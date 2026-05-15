<?php
/**
 * @var \App\View\AppView $this
 */
$pageTitle = trim((string)$this->fetch('title'));
$fullTitle = $pageTitle !== '' ? $pageTitle . ' | ERMIS' : 'ERMIS';
$bodyClass = trim((string)$this->fetch('bodyClass'));
?>
<!DOCTYPE html>
<html lang="en">
<!-- Header -->
<?= $this->element('layout/head', ['title' => $fullTitle]) ?>
<body class="<?= h($bodyClass) ?>">
    <!-- Body -->
    <div class="app-shell auth-layout">
        <!-- Content -->
        <main class="app-content app-container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </main>
    </div>
    <!-- Scripts -->
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
