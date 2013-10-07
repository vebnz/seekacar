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
            $("#pickupdate, #dropoffdate").datepicker({
                dateFormat : 'mm/dd/yy',
                changeMonth : true,
                changeYear : true,
                minDate: 0,
            });
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
