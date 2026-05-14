<h1 class="page-name">Administration panel</h1>

<?php 
    include_once __DIR__ . '/../templates/bar.php';
?>

<h2>Search Appointments</h2>
<div class="search">
    <form class="form">
        <div class="form-field">
            <label for="date">Date</label>
            <input 
                type="date"
                id="date"
                name="date"
                value="<?php echo $date ?? ''; ?>"
            />
        </div>
    </form>
</div>

<div class="admin-apptm">
    <ul class="apptms">
        <?php 
            $idApptm = 0;
            foreach($appointments as $key => $appointment) {

                if($idApptm !== $appointment->id) {
                    $total = 0;
                ?>
                <li>
                    <p>Id: <span><?php echo $appointment->id; ?></span></p>
                    <p>Time: <span><?php echo $appointment->time; ?></span></p>
                    <p>client: <span><?php echo $appointment->client; ?></span></p>
                    <p>Email: <span><?php echo $appointment->email; ?></span></p>
                    <p>Phone: <span><?php echo $appointment->phone; ?></span></p>

                    <h3>Services</h3>
            <?php
                $idApptm = $appointment->id;
                } // End of If 
                    $total += $appointment->price;
                ?>
                    <p class="service"><?php echo $appointment->service . " " . " - $" . $appointment->price; ?></p>

                <?php 
                    $current = $appointment->id;
                    $next = $appointments[$key + 1]->id ?? 0;

                    if (isLast($current, $next)) { ?>
                        <p class="total">Total: <span>$<?php echo $total; ?></span></p>
                <?php }
            } // End of the foreach ?>
    </ul>
</div>