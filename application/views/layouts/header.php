<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= html_escape($title ?? 'Invoice & Budget') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark topbar px-3 sticky-top">
  <button class="btn btn-link text-white d-md-none p-0 me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"><i class="bi bi-list fs-3"></i></button>
  <a class="navbar-brand fw-semibold" href="<?= site_url('dashboard') ?>"><i class="bi bi-receipt-cutoff me-2"></i>Invoice & Budget</a>
  <div class="ms-auto d-flex align-items-center gap-2 text-white small"><span class="d-none d-sm-inline">Welcome, <?= html_escape($this->session->userdata('user_name')) ?></span><a class="btn btn-sm btn-outline-light" href="<?= site_url('logout') ?>">Logout</a></div>
</nav>
<?php
$current = trim($this->uri->segment(1));
$menu = array(
 'dashboard'=>array('Dashboard','bi-grid'),
 'sales-invoices'=>array('Sales invoices','bi-file-earmark-text'),
 'purchase-invoices'=>array('Purchase invoices','bi-bag-check'),
 'customers'=>array('Customers','bi-people'),
 'suppliers'=>array('Suppliers','bi-truck'),
 'products'=>array('Products / Services','bi-box-seam'),
 'budgets'=>array('Budgets','bi-wallet2'),
 'expenses'=>array('Expenses','bi-cash-stack'),
 'reports'=>array('Reports & analytics','bi-bar-chart'),
 'currency-rates'=>array('Currency API','bi-currency-exchange'),
);
?>
<div class="container-fluid"><div class="row">
<aside class="col-md-2 sidebar d-none d-md-block py-3">
<?php foreach($menu as $url=>$meta): ?><a class="<?= $current===$url?'active':'' ?>" href="<?= site_url($url) ?>"><i class="bi <?= $meta[1] ?>"></i><span><?= html_escape($meta[0]) ?></span></a><?php endforeach; ?>
</aside>
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar"><div class="offcanvas-header"><h5 class="offcanvas-title">Menu</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button></div><div class="offcanvas-body sidebar mobile-sidebar">
<?php foreach($menu as $url=>$meta): ?><a class="<?= $current===$url?'active':'' ?>" href="<?= site_url($url) ?>"><i class="bi <?= $meta[1] ?>"></i><span><?= html_escape($meta[0]) ?></span></a><?php endforeach; ?>
</div></div>
<main class="col-md-10 p-3 p-lg-4 app-main">
<?php if($this->session->flashdata('message')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= html_escape($this->session->flashdata('message')) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if($this->session->flashdata('error')): ?><div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i><?= html_escape($this->session->flashdata('error')) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if(validation_errors()): ?><div class="alert alert-danger"><?= validation_errors() ?></div><?php endif; ?>
