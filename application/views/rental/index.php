<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48131048-1', 'seekacar.co.nz');
  ga('send', 'pageview');

</script>
<div id="header" style="display:block;">
    <!--<div id="background-image"></div>-->
    <ul class="list-unstyled" id="bgimage">
        <li>
        <img src="images/background.jpg" height="700" width="1600" style="min-width: 1600px; top: -100.3658536585366px;">
        </li>
    </ul>
    <div class="search-form">
        <div class="contained well">
            <?php 
                if (validation_errors()) {
                    echo "<div class=\"error\">";
                    echo validation_errors(); 
                    echo "</div>";
                }
            ?>
            <?php echo form_open('rental') ?>
                <div class="row formc">
                    <div class="form-group col-lg-12">
                        <select id="pickuplocation" name="pickuplocation" class="form-control">
                            <?php foreach ($locations as $location): ?>
                                <option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-7">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" class="form-control" id="pickupdate"  name="pickupdate" placeholder="Pickup Date" value="<?php echo set_value('pickupdate'); ?>" />
                        </div>
                    </div>
                    <div class="form-group col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            <input type="text" class="form-control" id="pickuptime"  name="pickuptime"  placeholder="Time" />
                        </div>
                    </div>
                </div>
                <div class="row formc">
                    <div class="form-group col-lg-12">
                        <select id="dropofflocation" name="dropofflocation" class="form-control">
                            <?php foreach ($locations as $location): ?>
                                <option value="<?php echo $location['city']; ?>"><?php echo  $location['city']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-7">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input type="text" class="form-control" id="dropoffdate" name="dropoffdate" placeholder="Dropoff Date" value="<?php echo set_value('dropoffdate'); ?>" />
                        </div>
                    </div>
                    <div class="form-group col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            <input type="text" class="form-control" id="dropofftime" name="dropofftime" name="dropofftime" placeholder="Time" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                </div>
            </form>

        </div>
    </div>               
</div>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron ">
        <div class="container hidden-sm hidden-xs hidden-md">
            <div class="col-lg-12 centered">
				<div class="company col-lg-2">
					<img src="/images/ace.jpg" alt="Ace Rental Cars" />
                </div>
                <div class="company col-lg-2">
                    <img src="/images/apex.jpg" alt="Apex Rental Cars" />
                </div>
                <div class="company col-lg-2">
                    <img src="/images/britz.jpg" alt="Britz Rental Cars" />
                </div>
                <div class="company col-lg-2">
                    <img src="/images/budget.jpg" alt="Budget Rental Cars" />
                </div>
                <div class="company col-lg-2">
                    <img src="/images/omega.jpg" alt="Omega Rental Cars" />
                </div>
                <div class="company col-lg-2">
                    <img src="/images/pegasus.jpg" alt="Pegasus Rental Cars" />
                </div>
            </div>
        </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
          <h2>Search</h2>
          <p>Simply enter in the dates and times that you wish to rent a car and we'll return real-time real time results to you.</p>        
        </div>
        <div class="col-lg-4">
          <h2>Compare</h2>
          <p>Compare makes, models, sizes, and prices from a wide range of well known car rental companies in New Zealand.</p>
       </div>
        <div class="col-lg-4">
          <h2>Book</h2>
          <p>Once you've decided on a specific car and company, simply select the car and we'll send you directly to the companies website ready for you to book.</p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Seek A Car 2013</p>
      </footer>
    </div> <!-- /container -->

