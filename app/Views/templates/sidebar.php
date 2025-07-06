<?php
$user = isset($user) ? $user : (session('user') ?? []);
$userType = $user['user_type'] ?? null;
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url($userType ? $userType . '/dashboard' : '/') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <?php if ($userType === 'admin'): ?>
                <i class="fas fa-laugh-wink"></i>
            <?php elseif ($userType === 'doctor'): ?>
                <i class="fas fa-user-md"></i>
            <?php elseif ($userType === 'staff'): ?>
                <i class="fas fa-user-tie"></i>
            <?php elseif ($userType === 'patient'): ?>
                <i class="fas fa-user"></i>
            <?php else: ?>
                <i class="fas fa-home"></i>
            <?php endif; ?>
        </div>
        <div class="sidebar-brand-text mx-3">
            <?= ucfirst($userType ?? 'Perfect Smile') ?>
        </div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url($userType ? $userType . '/dashboard' : '/') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <?php if ($userType === 'admin'): ?>
        <div class="sidebar-heading">Management</div>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/patients') ?>"><i class="fas fa-users"></i><span>Patients Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/appointments') ?>"><i class="fas fa-calendar-alt"></i><span>Appointments Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/services') ?>"><i class="fas fa-stethoscope"></i><span>Services Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/waitlist') ?>"><i class="fas fa-clipboard-list"></i><span>Waitlist Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/procedures') ?>"><i class="fas fa-file-medical"></i><span>Procedures Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/records') ?>"><i class="fas fa-folder-open"></i><span>Records Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/invoice') ?>"><i class="fas fa-file-invoice-dollar"></i><span>Invoice Management</span></a></li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Administration</div>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/role-permission') ?>"><i class="fas fa-user-shield"></i><span>Role Permission Management</span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/branches') ?>"><i class="fas fa-code-branch"></i><span>Branches Management</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/settings') ?>"><i class="fas fa-cog"></i><span>Settings</span></a></li>
    <?php elseif ($userType === 'doctor'): ?>
        <div class="sidebar-heading">Management</div>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-user-injured"></i><span>Patients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-notes-medical"></i><span>Procedures</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-file-medical-alt"></i><span>Records</span></a></li>
    <?php elseif ($userType === 'staff'): ?>
        <div class="sidebar-heading">Management</div>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-users"></i><span>Patients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-file-invoice-dollar"></i><span>Invoices</span></a></li>
    <?php elseif ($userType === 'patient'): ?>
        <div class="sidebar-heading">My Account</div>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-calendar-check"></i><span>My Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-file-medical-alt"></i><span>My Records</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-user-cog"></i><span>Profile</span></a></li>
    <?php endif; ?>
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign Out</span>
        </a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul> 