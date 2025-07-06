<?= view('templates/header') ?>
<div id="wrapper">
    <?= view('templates/sidebar', ['user' => $user]) ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container mt-5">
                <h1>Patients Management</h1>
                <p>This is a blank page for managing patients.</p>
            </div>
        </div>
    </div>
</div>
<?= view('templates/footer') ?> 