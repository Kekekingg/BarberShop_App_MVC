<h1 class="page-name">Update Service</h1>
<p class="page-description">Modify the form values</p>

<?php  
    include_once __DIR__ . '/../templates/bar.php';
    include_once __DIR__ . '/../templates/alerts.php';
?>

<!-- -->
<form method="POST" class="form">
    <?php include_once __DIR__ . '/form.php'; ?>
    <input 
        type="submit"
        class="button"
        value="Update Service"
    />
</form>