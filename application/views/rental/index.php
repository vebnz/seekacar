<!-- testing -->
<script type='text/javascript' language='javascript'>
$(document).ready(function() {
	$('#submit').click(function(){ 
		$('#result_table').empty();
		$('#result_table').append("Fetching Cars...");
		$.ajax({
            url: 'rental/get_cars',
            type:'POST',
			data: {pickuplocation: $('#pickuplocation').val(),	pickupdate: $('#pickupdate').val(),	pickuptime: $('#pickuptime').val(),	dropofflocation: $('#dropofflocation').val(), dropoffdate: $('#dropoffdate').val(),	dropofftime: $('#dropofftime').val()},
            success: function(data){
					$('#result_table').empty();
                    $('#result_table').append(output_string);
            } // End of success function of ajax form
        }); // End of ajax call  
	});
});
</script>
	
<h3>Welcome to Rental Cars</h3>
Search for a Rental Car!

<?php echo validation_errors(); ?>
<?php echo form_open('rental/index') ?>

<h3>Pick-Up</h3>

<label for="pickuplocation">Pick-Up Location:</label> 
<select id="pickuplocation" name="pickuplocation">
<?php foreach ($locations as $location): ?>
<option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
<?php endforeach ?>    
</select><br />

<label for="pickupdate">Pick-Up Date:</label>
<input id="pickupdate"  name="pickupdate" type="text" /><br />

<label for="pickuptime">Pick-Up Time:</label> 
<select id="pickuptime"  name="pickuptime"><option value="1">1:00</option><option value="2">2:00</option><option value="3">3:00</option></select><br />

<h3>Drop-Off</h3>

<label for="dropofflocation">Drop-Off Location:</label> 
<select id="dropofflocation"  name="dropofflocation">
<?php foreach ($locations as $location): ?>
<option value="<?php echo $location['city']; ?>"><?php echo $location['city']; ?></option>
<?php endforeach ?>    
</select><br />

<label for="dropoffdate">Drop-Off Date:</label>
<input id="dropoffdate" name="dropoffdate" type="text" /><br />

<label for="dropofftime">Drop-Off Time:</label> 
<select id="dropofftime" name="dropofftime"><option value="1">1:00</option><option value="2">2:00</option><option value="3">3:00</option></select><br />

<input type="button" id="submit" name="submit" value="Search Cars" /> 

</form>

<div id="result_table"></div>    
<br /><br />