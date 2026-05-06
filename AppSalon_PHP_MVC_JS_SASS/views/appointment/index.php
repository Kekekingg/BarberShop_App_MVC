<h1 class="page-name">Create A New Appointment</h1>
<p class="page-description">Select your services below enter your details</p>

<div id="app">
    <nav class="tabs">
        <button class="current" type="button" data-step="1">Services</button>
        <button type="button" data-step="2">Appointment information</button>
        <button type="button" data-step="3">Summary</button>
    </nav>
    <div id="step-1" class="section">
        <h2>Services</h2>
        <p class="text-center">Select your services below</p>
        <div id="services" class="list-services"></div>
    </div>

    <div id="step-2" class="section">
        <h2>Your Details and Appointment</h2>
        <p class="text-center">Enter your details and the date of your appointment</p>

        <form class="form">
            <div class="form-field">
                <label for="name">Name</label>
                <input 
                    id="name"
                    type="text"
                    placeholder="Your Name"
                    value="<?php echo $name ?? null ?>"
                    disabled
                />
            </div>

            <div class="form-field">
                <label for="date">Date</label>
                <input 
                    id="date"
                    type="date"
                />
            </div>

            <div class="form-field">
                <label for="time">Time</label>
                <input 
                    id="time"
                    type="time"
                />
            </div>
        </form>
    </div>

    <div id="step-3" class="section">
        <h2>Summary</h2>
        <p class="text-center">Check if the information is correct</p>
    </div>

    <div class="pagination">
        <button
            id="previous"
            class="button"
        >&laquo; Previous</button>

        <button
            id="next"
            class="button"
        >Next &raquo;</button>
    </div>

    
</div>

<?php 
    $script = "
        <script src='build/js/app.js'></script>
    ";
?>