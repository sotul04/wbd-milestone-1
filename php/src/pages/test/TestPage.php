<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<section>
    <h2>Test your Code here</h2>
    <button
        onclick="showModal('Delete File', 'Are you sure you want to delete this file?', () => { alert('File deleted!'); })">Show Modal</button>
    <button onclick="createToast('This is the success toast', 'success');">Show Toast</button>
</section>

<?php require_once __DIR__ . "/../template/footer.php" ?>