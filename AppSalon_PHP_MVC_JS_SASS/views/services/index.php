<h1 class="page-name">Services Page</h1>
<p class="page-description">Service Administration</p>

<?php  
    include_once __DIR__ . '/../templates/bar.php';
?>

<ul class="services">
    <?php foreach($services as $service) { ?>
        <li>
            <p>Name: <span><?php echo $service->servicename ?></span></p>
            <p>Price: <span>$<?php echo $service->price ?></span></p>

            <div class="actions">
                <a class="button a-button" href="/services/update?id=<?php echo $service->id; ?>">Update</a>

                <form action="/services/delete" method="POST">
                    <input type="hidden" name="id" value="<?php echo $service->id ?>">
                    <input type="submit" value="Delete" class="delete-button">
                </form>
            </div>
        </li>
    <?php } ?>
</ul>