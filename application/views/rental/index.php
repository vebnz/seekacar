<script type='text/javascript' language='javascript'>
$(document).ready(function() {	
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#pickupdate').datepicker({
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		checkin.hide();
		$('#dropoffdate')[0].focus();
	}).data('datepicker');
	var checkout = $('#dropoffdate').datepicker({
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');
});
</script>
 		<div class="app_view">
		<div class="hero">
			<ul class="slideshow">
				<li>
					<img alt="Writer's Studio" src="/images/background.jpg" height="700" width="1600" style="min-width: 1600px; top: -100.3658536585366px;">
				</li>
			</ul>
			<div class="search-form">
				<div class="contained well">
					<h2>Find Rentals</h2>
					<?php echo validation_errors(); ?>
                    <?php echo form_open('rental') ?>

						<fieldset>
							<div class="clearfix">
                                <select id="pickuplocation" name="pickuplocation" class="input-xlarge">
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
                                    <?php endforeach ?>  
                                </select>
							</div>
							<div class="clearfix">
                            <input class="input-medium date start" id="pickupdate"  name="pickupdate" type="text" placeholder="Pickup Date" value="<?php echo set_value('pickupdate'); ?>"> 
                                <input class="input-small" id="pickuptime"  name="pickuptime" type="text" placeholder="Pickup Time">
							</div>
							<div class="clearfix">
                                <select id="dropofflocation" name="dropofflocation" class="input-xlarge">
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?php echo $location['city']; ?>"><?php echo $location['city']; ?></option>
                                    <?php endforeach ?> 
                                </select>
							</div>
							<div class="clearfix">
                            <input class="input-medium" id="dropoffdate" name="dropoffdate" type="text" placeholder="Dropoff Date" value="<?php echo set_value('dropoffdate'); ?>"> 
                                <input class="input-small" id="dropofftime" name="dropofftime" type="text" placeholder="Dropoff Time">
							</div>
							<div class="clearfix">
								<button class="btn btn-large btn-primary" id="submit" name="submit" type="submit">Search</button>
							</div>
						</fieldset>
					</form>
					</div>
				</div>               
            
		</div>
		</div>
		<div class="car-brands pagination-centered">
                    <img src="/images/ace.jpg" alt="Ace Rental Cars" /><img src="/images/apex.jpg" alt="Apex Rental Cars" /><img src="/images/britz.jpg" alt="Britz Rental Cars" /><img src="/images/budget.jpg" alt="Budget Rental Cars" /><img src="/images/omega.jpg" alt="Omega Rental Cars" /><img src="/images/thrifty.jpg" alt="Thrifty Rental Cars" />
        </div>
          <hr>
		    <div class="main">
            <div class="row-fluid boxes">
            <div class="span4">
              <h2>Search</h2>
              <p>Search destinations, dates, and times.</p>
              <p><a class="btn btn-success btn-large" href="#">Search</a></p>
            </div><!--/span-->
            <div class="span4">
              <h2>Compare</h2>
              <p>Compare results from multiple car rental companies.</p>
              <p><a class="btn btn-success btn-large" href="#">Compare</a></p>
            </div><!--/span-->
            <div class="span4">
              <h2>Rent!</h2>
              <p>Choose the car you like and book it.</p>
              <p><a class="btn btn-success btn-large" href="#">Rent</a></p>
            </div><!--/span-->
          </div><!--/row-->
          <hr />
            <h1>Pick 'a' Rental!</h1>
            <p>Search, compare, and choose rental cars from multiple car rental agencies on specified dates and times of your choosing.</p>

          </div>
          
 
