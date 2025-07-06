<?= view('templates/header') ?>
<div id="wrapper">
    <?= view('templates/sidebar', ['user' => $user]) ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container mt-5">
                <h1>Patient Dashboard</h1>
                <p>Welcome, <?= esc($user['name'] ?? 'Patient') ?>!</p>
                <p>This is your dashboard. Add your widgets and stats here.</p>
            </div>
        </div>
    </div>
</div>
<?= view('templates/footer') ?> 