


/*=============================================================
    Authour URI: www.binarytheme.com
    License: Commons Attribution 3.0

    http://creativecommons.org/licenses/by/3.0/

    100% To use For Personal And Commercial Use.
    IN EXCHANGE JUST GIVE US CREDITS AND TELL YOUR FRIENDS ABOUT US
   
    ========================================================  */

document.addEventListener('DOMContentLoaded', function () {
    const queryParams = getQueryParams();

    // Only show alert if there are query parameters
    if (Object.keys(queryParams).length > 0) {
        // Format the parameters for display
        let alertMessage = "";
        for (const [key, value] of Object.entries(queryParams)) {
            alertMessage += `${key}: ${value}\n`;
        }

        // Show alert and clear params when dismissed
        alert(alertMessage);
        clearQueryParams();
    }
});
(function ($) {
    "use strict";
    var mainApp = {

        main_fun: function () {

            /*====================================
              LOAD APPROPRIATE MENU BAR
           ======================================*/
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse')
                } else {
                    $('div.sidebar-collapse').removeClass('collapse')
                }
            });



        },

        initialization: function () {
            mainApp.main_fun();

        }

    }
    // Initializing ///

    $(document).ready(function () {
        mainApp.main_fun();
    });

}(jQuery));
