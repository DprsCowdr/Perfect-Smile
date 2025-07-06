<?= view('templates/header') ?>
<div id="wrapper">
    <?= view('templates/sidebar', ['user' => $user]) ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container mt-5">
                <h1>Staff Dashboard</h1>
                <p>Welcome, <?= esc($user['name'] ?? 'Staff') ?>!</p>
                <p>This is your dashboard. Add your widgets and stats here.</p>
            </div>
        </div>
    </div>
</div>
<?= view('templates/footer') ?> 