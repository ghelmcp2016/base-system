<?php
/**
 * @var \App\View\AppView $this
 * @var string $title
 */
?>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?></title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('app') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
