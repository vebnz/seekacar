<script type='text/javascript' language='javascript'>

var carArray = new Array();
var filteredCar = new Array();

var minPrice = 0, // Will be done by slider
    	maxPrice = 300, // Will be done by slider
		minSize = 0, // Will be done by slider
		maxSize = 10, // Will be done by slider        
		pricesort = 0,
		namesort = 0
		    
$(document).ajaxStart(function () {
    $('#result_table').fadeIn();
});
$(document).ajaxStop(function () {
    $('#result_table').fadeOut();
		//$('#totalCount').text('Showing 1 - 10 of ' + carArray.length); 
	});
$(document).ready(function() {	

	$('#searchForm').hide();

	companies = $("input[name='companies']:checked").map(getVal).get();
				
	var changePriceSlide = function() {
		var prices = priceSlider.getValue();
		minPrice = prices[0];
		maxPrice = prices[1];
	};

	var priceSlider = $('#slprice').slider()
                .on('slide', changePriceSlide)
                .data('slider');

	var changeSizeSlide = function() {
		var sizes = sizeSlider.getValue();
		minSize = sizes[0];
		maxSize = sizes[1];
	}

	var sizeSlider = $('#slsize').slider()
		.on('slide', changeSizeSlide)
		.data('slider');

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
							varCompanyImg = '';
							if (car.company == "AceRentals") {
								varCompanyImg = 'ace.jpg';
							} else if (car.company == "Apex") {
								varCompanyImg = 'apex.jpg';
							} else if (car.company == "Budget") {
								varCompanyImg = 'budget.jpg';
							} else if (car.company == "Britz") {
								varCompanyImg = 'britz.jpg';
							} else if (car.company == "Pegasus") {
								varCompanyImg = 'pegasus.jpg';
							} else if (car.company == "Omega") {
								varCompanyImg = 'omega.jpg';
							}
							$('#carTable tr:last').after('<tr><td>&nbsp;</td><td>' + car.company + '</td><td>' + car.title + '</td><td>' + car.type + '</td><td>' + car.gearbox + '</td><td>' + car.size + '</td><td>' + car.price + '</td><td><a href="' + car.url + '">GO</a></td></tr>');
							$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type"><img style="width: 40px; height: 20px; margin-right: 10px;" src="/images/' + varCompanyImg + '" /> ' + car.title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car.image + '"></div><figcaption class="clearfix"><div class="details hidden-xxs"><h1>' + car.type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car.size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car.gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car.url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car.price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');
							var companyCars = [{'company':car.company, 'title':car.title, 'type':car.type, 'gearbox':car.gearbox, 'size':car.size, 'price':car.price, 'image':car.image, 'url':car.url}];
							carArray.push(companyCars);
							filteredCar.push(companyCars);
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
		filteredCar.sort(function(a, b){
    		var a1= a[0].company, b1= b[0].company;
    		if(a1== b1) return 0;
    			return a1> b1? 1: -1;
		});
		
		if (namesort==1) {
			filteredCar.reverse();
			namesort=0;
		} else {
			namesort=1;
		}
		
		// re-draw the results
		$.each(filteredCar, function(i, car)  {
			varCompanyImg = '';
			if (car[0].company == "AceRentals") {
					varCompanyImg = 'ace.jpg';
			} else if (car[0].company == "Apex") {
					varCompanyImg = 'apex.jpg';
			} else if (car[0].company == "Budget") {
					varCompanyImg = 'budget.jpg';
			} else if (car[0].company == "Britz") {
					varCompanyImg = 'britz.jpg';
			} else if (car[0].company == "Pegasus") {
					varCompanyImg = 'pegasus.jpg';
			} else if (car[0].company == "Omega") {
					varCompanyImg = 'omega.jpg';
			}
		
			$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type"><img style="width: 40px; height: 20px; margin-right: 10px;" src="/images/' + varCompanyImg + '" /> ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details hidden-xxs"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');	
		});
    });
	
	$('#sortByPrice').click(function() {
        // clear results
        $('#vehicles-list').empty();
        // sort the array by company name
        filteredCar.sort(function(a, b){
            var a1= parseInt(a[0].price), b1= parseInt(b[0].price);
            if(a1== b1) return 0;
                return a1> b1? 1: -1;
        });
		
		if (pricesort==1) {
			filteredCar.reverse();
			pricesort=0;
		} else {
			pricesort=1;
		}
                
        // re-draw the results
         $.each(filteredCar, function(i, car)  {
 
 	        varCompanyImg = '';
			if (car[0].company == "AceRentals") {
					varCompanyImg = 'ace.jpg';
			} else if (car[0].company == "Apex") {
					varCompanyImg = 'apex.jpg';
			} else if (car[0].company == "Budget") {
					varCompanyImg = 'budget.jpg';
			} else if (car[0].company == "Britz") {
					varCompanyImg = 'britz.jpg';
			} else if (car[0].company == "Pegasus") {
					varCompanyImg = 'pegasus.jpg';
			} else if (car[0].company == "Omega") {
					varCompanyImg = 'omega.jpg';
			}
 
            $('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type"><img style="width: 40px; height: 20px; margin-right: 10px;" src="/images/' + varCompanyImg + '" /> ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details hidden-xxs"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');
		});
    });

	function getVal() {
		return $(this).val();
	}			
		
    predicates = [
        function checkMinPrice(carArray)
        {
        	return parseFloat(carArray.price) >= minPrice;
        },
        function checkMaxPrice(carArray)
        {
        	return parseFloat(carArray.price) <= maxPrice;
        },
		function checkMinSize(carArray)
        {
        	return parseFloat(carArray.size) >= minSize;
        },
        function checkMaxSize(carArray)
        {
        	return parseFloat(carArray.size) <= maxSize;
        },
        function checkColor(carArray)
        {
        	return $.inArray(carArray.company, companies) !== -1;
        }
    ],
    filteredCar;
	
	$('#filters').click(function() {
	
		// clear results
		$('#vehicles-list').empty();
		
		// reset the filtered car array
		filteredCar = carArray;
		
		// get the list of companies to display
		companies = $("input[name='companies']:checked").map(getVal).get()
		
		// filter the cars on price/size sliders and companies checkboxes
		filteredCar = $.grep(carArray, function(element, index) {
			for (var i = 0; i < predicates.length; i++) {
				if (!predicates[i](element[0])) return false;
			}
			return true;
		});
		
		// finally display the cars
		$.each(filteredCar, function(i, car) {
 
 			varCompanyImg = '';
			if (car[0].company == "AceRentals") {
					varCompanyImg = 'ace.jpg';
			} else if (car[0].company == "Apex") {
					varCompanyImg = 'apex.jpg';
			} else if (car[0].company == "Budget") {
					varCompanyImg = 'budget.jpg';
			} else if (car[0].company == "Britz") {
					varCompanyImg = 'britz.jpg';
			} else if (car[0].company == "Pegasus") {
					varCompanyImg = 'pegasus.jpg';
			} else if (car[0].company == "Omega") {
					varCompanyImg = 'omega.jpg';
			}
 
  			$('#vehicles-list').append('<div class="vehicle"><article class=" "><div class="row clearfix"><div class="vehicle-header clearfix"><span class="vehicle-type"><img style="width: 40px; height: 20px; margin-right: 10px;" src="/images/' + varCompanyImg + '" /> ' + car[0].title + '</span></div><div class="vehicle-info"><figure><div class="image"><img src="' + car[0].image + '"></div><figcaption class="clearfix"><div class="details hidden-xxs"><h1>' + car[0].type + '</h1><div class="features hidden-item"><div class="wrapper"><ul><li>Size: ' + car[0].size + '</li><li>Luggage: 1 Large Suitcase</li><li>Gearbox: ' + car[0].gearbox + '</li></ul></div></div></div></figcaption></figure><div class="pricing"><div class="single"><div class="wrapper"><div><a target="_blank" href="' + car[0].url + '" class="primary priced btn btn-primary">Select</a><strong class="price">$' + car[0].price + '</strong><span> NZD</span></div></div></div></div></div></div></article></div>');  
  		});
	});
	
	$('#refineSearch').click(function() {
		$('#itinerary').hide();
		$('#searchForm').show();
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
				<div id="topLeft">
					<div id="itinerary">
						<ul>
							<li>Pickup Location: <?php echo $plocation; ?> </li>
							<li>Pickup Time: <?php echo $pudate . ' ' . $putime; ?> </h1>
							<li>Drop-off Location: <?php echo $dlocation; ?> </li>
							<li>Drop-off Time: <?php echo $dodate . ' ' . $dotime ?> </li>
						</ul>
						<p><a id="refineSearch">Refine search</a></p> 
					</div>
					<div id="searchForm">
						<?php 
							if (validation_errors()) {
								echo "<div class=\"error\">";
								echo validation_errors(); 
								echo "</div>";
							}
						?>
						<?php echo form_open('rental') ?>
							<div class="row">
								<div class="form-group col-lg-10 resultform">
									<select id="pickuplocation" name="pickuplocation" class="form-control">
										<?php foreach ($locations as $location): ?>
											<option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-6 resultform">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input type="text" class="form-control" id="pickupdate"  name="pickupdate" placeholder="Pickup Date" value="<?php echo set_value('pickupdate'); ?>" />
									</div>
								</div>
								<div class="form-group col-lg-5 resultform">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
										<input type="text" class="form-control" id="pickuptime"  name="pickuptime"  placeholder="Time" value="<?php echo set_value('pickuptime'); ?>" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-10 resultform">
									<select id="dropofflocation" name="dropofflocation" class="form-control">
										<?php foreach ($locations as $location): ?>
											<option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-6 resultform">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input type="text" class="form-control" id="dropoffdate" name="dropoffdate" placeholder="Dropoff Date" value="<?php echo set_value('dropoffdate'); ?>" />
									</div>
								</div>
								<div class="form-group col-lg-5 resultform">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
										<input type="text" class="form-control" id="dropofftime" name="dropofftime" name="dropofftime" placeholder="Time" value="<?php echo set_value('dropofftime'); ?>" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-10 resultform">
									<button type="submit" class="btn btn-primary btn-block">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<h2>Filter Results</h2>
				<div id="companies">
				<a id="sortByName">Sort by Company</a><br/>
				<a id="sortByPrice">Sort by Price</a><br />
				<label class="checkbox inline">
					<input value='AceRentals' name='companies' type="checkbox" checked> Ace Rentals
				</label>
				<label class="checkbox inline">
					<input value='Pegasus' name='companies' type="checkbox" checked> Pegasus
				</label>
				<label class="checkbox inline">
					<input value='Omega' name='companies' type="checkbox" checked> Omega
				</label>
				<label class="checkbox inline">
					<input value='Budget' name='companies' type="checkbox" checked> Budget
				</label>
				<label class="checkbox inline">
					<input value='Britz' name='companies' type="checkbox" checked> Britz
				</label>
				<label class="checkbox inline">
					<input value='Apex' name='companies' type="checkbox" checked> Apex
				</label>
				</div>
				<div>
					Price: <b>$0</b>&nbsp; <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="500" data-slider-step="5" data-slider-value="[0,500]" id="slprice" >&nbsp; <b>$500</b><br />
					Size: <b>1</b>&nbsp; <input type="text" class="span2" value="" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="[1,10]" id="slsize" >&nbsp; <b>10</b>
				</div>
				<div><button id="filters" class="primary priced btn btn-primary">Filter</button></div>
			</div>
			<div id="cars" class="col-lg-8">				
				<div id="debug">
					<div id="result_table" style="clear: both; z-index: 999;"><p style="text-align: center;"><img src="/images/loading.gif" id="img-load" /> Finding Cars...</p></div>
					<div id="vehicles-list">

					</div>				
				</div>
			</div>
		</div>
		<hr>
		<footer>
			<p>&copy; Seek A Car 2013</p>
		</footer>
    </div> <!-- /container -->
