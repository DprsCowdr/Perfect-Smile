<?= view('templates/header') ?>
<div id="wrapper">
    <?= view('templates/sidebar', ['user' => $user]) ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div style="background:#F5ECFE; min-height: 100vh; width: 100%;">
                <div class="container">
                    <?= view('templates/patientsTable', ['patients' => $patients, 'user' => $user]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .table thead th { border: none !important; }
    .table td, .table th { vertical-align: middle !important; }
    .btn-primary { background: #c7aefc !important; border: none !important; }
    .btn-primary:hover { background: #a47be5 !important; }
</style>


<?= view('templates/footer') ?> 