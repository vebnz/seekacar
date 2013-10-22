<script type='text/javascript' language='javascript'>

var carArray = new Array();
var disArray = new Array();

$(document).ajaxStart(function () {
    $('#result_table').fadeIn();
});
$(document).ajaxStop(function () {
    $('#result_table').fadeOut();
    //$('#totalCount').text('Showing 1 - 10 of ' + carArray.length); 
});
$(document).ready(function() {	
	$.ajax({
        url: 'list_companies',
        type:'POST',
		dataType: 'json',
		data: {pickuplocation: "<?php echo $plocation; ?>", dropofflocation: "<?php echo $dlocation; ?>"},
		success: function(companies){			
			$.each(companies, function(i, company) {					
                $.ajax({
                    url: 'get_cars',
                    type: 'POST',
                    dataType: 'json',
		    timeout: 30000, // 30 second timeout
                    data: {name: company.name, puc: company.puc, doc: company.doc, pickupdate: "<?php echo $pudate; ?>", pickuptime: "<?php echo $putime; ?>", dropoffdate: "<?php echo $dodate; ?>", dropofftime: "<?php echo $dotime; ?>"},
                    success: function(cars){

						$.each(cars, function(i, car) {
							$('#carTable tr:last').after('<tr><td>&nbsp;</td><td>' + car.company + '</td><td>' + car.title + '</td><td>' + car.type + '</td><td>' + car.gearbox + '</td><td>' + car.size + '</td><td>' + car.price + '</td><td><a href="' + car.url + '">GO</a></td></tr>');
							$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type">[' + car.company + '] ' + car.title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car.image + '"></div><figcaption class="clearfix"><div class="details"><h1>' + car.type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car.size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car.gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car.url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car.price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');
							var companyCars = [{'company':car.company, 'title':car.title, 'type':car.type, 'gearbox':car.gearbox, 'size':car.size, 'price':car.price, 'image':car.image, 'url':car.url}];
							carArray.push(companyCars);
							disArray.push(companyCars);
						});							
					}
			    });
		    });	
		} // End of success function of ajax 
	}); // End of ajax call  
    	$('#sortByName').click(function() {
		// clear results
      		$('#vehicles-list').empty();
		// sort the array by company name
		disArray.sort(function(a, b){
    			var a1= a[0].company, b1= b[0].company;
    			if(a1== b1) return 0;
    				return a1> b1? 1: -1;
			});
		
		// re-draw the results
		$.each(disArray, function(i, car)  {
			$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type">[' + car[0].company + '] ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');	
		});
    	});
	$('#sortByPrice').click(function() {
                // clear results
                $('#vehicles-list').empty();
                // sort the array by company name
                disArray.sort(function(a, b){
                        var a1= a[0].price, b1= b[0].price;
                        if(a1== b1) return 0;
                                return a1< b1? 1: -1;
                        });
                
                // re-draw the results
                $.each(disArray, function(i, car)  {
                $('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type">[' + car[0].company + '] ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');
                });
        });
	$('#companies input[type=checkbox]').change(function() {
    		$('#vehicles-list').empty();
		disArray = carArray;
    	
		$("#companies input[type=checkbox]:checked").each(function () {
			var name = $(this).val();
        		disArray = $.grep(carArray, function( a ) {
  				return a[0].company == name;
			});
			$.each(disArray, function(i, car) {
        			$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type">[' + car[0].company + '] ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');  
			});
    		});

	});
});
</script>
<div class="jumbotron">
      <div class="container">

      </div>
</div>

    <div class="container">
		<!-- Example row of columns -->
		<div class="row">
			<div class="col-lg-4">
				<h2>Itinerary</h2>
				<div>
					<ul>
						<li>Pickup Location: <?php echo $plocation; ?> </li>
						<li>Pickup Time: <?php echo $pudate . ' ' . $putime; ?> </h1>
						<li>Drop-off Location: <?php echo $dlocation; ?> </li>
						<li>Drop-off Time: <?php echo $dodate . ' ' . $dotime ?> </li>
					</ul>
					<p><a href="">Refine search</a></p> 
				</div>
				<h2>Filter Results</h2>
				<div id="companies">
				<label class="checkbox inline">
					<input value='AceRentals' type="checkbox" checked> Ace Rentals
				</label>
				<label class="checkbox inline">
					<input value='Pegasus' type="checkbox" checked> Pegasus
				</label>
				<label class="checkbox inline">
					<input value='Omega' type="checkbox" checked> Omega
				</label><br/>
				<label class="checkbox inline">
					<input value='Budget' type="checkbox" checked> Budget
				</label>
				<label class="checkbox inline">
					<input value='Britz' type="checkbox" checked> Britz
				</label>
				<label class="checkbox inline">
					<input value='Apex' type="checkbox" checked> Apex
				</label>
				</div>
			</div>
			<div id="cars" class="col-lg-8">				
				<div id="debug">
					<div id="vehicles-list">

					</div>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="carTable">
						<thead>
							<tr>
								<th>Image</th>
								<th>Company Name</th>
								<th>Title</th>
								<th>Type</th>
								<th>Gearbox</th>
								<th>Size</th>
								<th>Price</th>
								<th>URL</th>
							</tr>		
						</thead>		
						<tbody>
							<tr id="result_table"><td colspan="8" style="text-align: center;"><img src="http://www.sanbaldo.com/wordpress/wp-content/bigrotation2.gif" id="img-load" /> Finding Cars...</td></tr>
						</tbody>
					</table>	
				</div>
			</div>
		</div>
		<hr>
		<footer>
			<p>&copy; Seek A Car 2013</p>
		</footer>
    </div> <!-- /container -->
