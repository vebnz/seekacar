/*
 |  Wrap the entire site code in it's own state, more information here:
 |  http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
 */
var DEFAULTAPP = (function ($) {

    var my = {};
    
    /*
     |  Main function called by document ready.
     */
    default_init = function() {

        /* Javascript goes in here */
		$(function() {	
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#pickupdate').datepicker({
				format: 'dd/mm/yyyy',
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
				format: 'dd/mm/yyyy',
				onRender: function(date) {
					return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
				}
			}).on('changeDate', function(ev) {
				checkout.hide();
			}).data('datepicker');
		});

        $(function() {
            $('#pickuptime, #dropofftime').timepicker({ 'scrollDefaultNow': true });
        });

    }


    /*
     |  Main function call once page loaded.
     */
    $(document).ready(function() {
        default_init();
    });


    /*
     |  return this instance so public functions can be called externally
     */
    return my;

}(jQuery));
