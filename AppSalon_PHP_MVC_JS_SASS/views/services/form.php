<div class="form-field">
    <label for="servicename">Name</label>
    <input 
        type="text"
        id="servicename"
        placeholder="Service Name"
        name="servicename"
        value="<?php echo isset($servicename) ? $servicename->servicename : '' ?>"
    />
</div>

<div class="form-field">
    <label for="price">Price</label>
    <input 
        type="number"
        id="price"
        placeholder="Service Price"
        name="price"
        value="<?php echo isset($servicename) ? $servicename->price : '' ?>"
    />
</div>