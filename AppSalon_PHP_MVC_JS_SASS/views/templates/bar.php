<div class="bar">
    <p>Hi: <?php echo $name ?? ''; ?></p>

    <a class="button" href="/logout">Logout</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="service-bar">
        <a href="/admin" class="button">View Appointments</a>
        <a href="/services" class="button">View Services</a>
        <a href="/services/create" class="button">New Service</a>
    </div>
<?php } ?>