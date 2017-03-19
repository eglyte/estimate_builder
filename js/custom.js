$(document).ready(function() {

    var result = 199,
        $top = $('#message').offset().top,
        templateL = $('.bodyLocation .location:first').clone(),
        locationsCount = $('.bodyLocation .location').length,
        devicePrice = 0,
        nuOfDevices = 0;
    var offsetX = Math.round($('#ajax-contact').offset().top - 20);

    // add new location
    $('body').on('click', '.addLocation', addLocation);

    //remove location
    $('.bodyLocation').on('click', '.removeLocation', removeLocation);

    // get device price
    $('#calc').on('change', '.selectDevice', getPrice);
    // get number of devices and calculate total price
    $('#calc').on('change', '.numberDevices', showPrice);

    // reset form and calc
    $('form').on('click', 'button[type=reset]',function() { 
        if (locationsCount > 1) {
            $('.bodyLocation .location:not(:first)').remove();
        }
        setTimeout(function(){
            priceChange();
            deviceChange();
        }, 300);
        $('html, body').stop().animate({scrollTop: offsetX}, 2000);
    });

    // get device price
    function getPrice( n,p,tp ) {
        var $set = $(this).closest('.step'),
            $el = $set.find('.selectDevice'),
            $nd = $set.find('.numberDevices');
        if (jQuery.type(n) == 'object') {             
            nuOfDevices = 1;
            $nd.val(nuOfDevices);
        } else {
            nuOfDevices = n;
        }             

            // assign device price to val if device selected 
        if ( $el.is(':checkbox') && $el.prop('checked') ) {
            var devicePrice = $el.data('value'); 
            //console.log($el.data('value')) ;          
        } else if ($el.is('select') && $el.val() != '') {
            var devicePrice = $el.find(':selected').data('value'); 
            //console.log($el.find(':selected').data('value')) ; 
        } else {
            var devicePrice = 0;            
            //$el.next('.help-block').css('display','block').parent().addClass('has-error');
        }

        if (p != undefined) {            
            devicePrice = p;
        } 

        //console.log('nuOfDevices - ' + nuOfDevices);
        //console.log('devicePrice - ' + devicePrice);
        var price = (devicePrice * nuOfDevices).toFixed(2);
        if (tp != undefined) {            
            price = tp;
        } 
        
        var maxDevices = parseInt( $nd.attr('max') );  
        if (nuOfDevices > maxDevices) {
            $nd.parent().addClass('has-error').find('.help-block').show();
            //console.log(nuOfDevices > maxDevices);
        } else if(devicePrice == 0 || devicePrice == '') {
            //console.log('devicePrice - ' + devicePrice);
            //$el.next('.help-block').css('display','block').parent().addClass('has-error');
            $set.find('.price').val(price);
            $nd.val('');
            //priceChange(); 
        } else {   
            $nd.parent().removeClass('has-error').find('.help-block').hide();         
            $set.find('.price').val(price);
            //priceChange();            
        } 
        priceChange(); 
        deviceChange();   
    };

    // get price and calculate total price    
    function showPrice() {
        var $set = $(this).closest('.step'),
            $el = $set.find('.selectDevice'),
            $nd = $set.find('.numberDevices'),
            nuOfDevices = $nd.val();
        
        if ( $el.is(':checkbox') && $el.prop('checked') ) {
            var devicePrice = $el.data('value');        
        } else if ($el.is('select') && $el.val() != '') {
            var devicePrice = $el.find(':selected').data('value'); 
        } else {
            var devicePrice = 0;            
        }

        var price = (devicePrice * nuOfDevices).toFixed(2);
        $set.find('.price').val(price);
        //console.log(price);
        getPrice(nuOfDevices,devicePrice,price);

    };
    

    // calculate total area devices price, change total
    function priceChange() {
        var $set = $('.step'),
            $price = 0;

        // get total price of area devices    
        $set.each( function(index, value) {
            $price = parseFloat($(this).find('.price').val()) + $price;
        });

        // add area devices prices to total result
        $totalResult = result + $price;
        $('body').find('#result input').val($totalResult.toFixed(2));
        if ($totalResult > 199) {
            $("#message").removeClass("bg-info").addClass("bg-success");
        } else {
            $("#message").removeClass("bg-success").addClass("bg-info");
        };
    };

    // calculate total devices
    function deviceChange() {
        var $set = $('.step'),
            $totalNumberOfDevices = 0;
        $set.each( function(index, value) {
            var $el = $(this).find('.selectDevice');
            if ( (($el.is(':checkbox') && $el.prop('checked')) || ($el.is('select') && $el.val() != '')) && $(this).find('.numberDevices').val() ) {
                $totalNumberOfDevices = parseInt( $(this).find('.numberDevices').val() ) + $totalNumberOfDevices;

                if ($totalNumberOfDevices > 32) {
                    $('.maxNumber').text($totalNumberOfDevices);
                    $('#maxDevice').modal('toggle');
                    $('form').find('button[type=submit]').prop('disabled',true);
                    return false; 
                } else {
                    $('form').find('button[type=submit]').prop('disabled',false);
                    return true; 
                }
            }  
   
            
        });

    }

    // show/hide location buttons
    function buttons(e) {
        if (locationsCount == 1) {
            $('.removeLocation').hide();
        } else {
            $('.bodyLocation .location:not(:first)').find('.removeLocation').show();
        };
        if (locationsCount == 10) {
            $('.addLocation').prop('disabled',true);
        } else {
            $('.bodyLocation').next().find('.addLocation').prop('disabled',false);
        }        
    };

    //add new location
    function addLocation() {
        
        var location = templateL.clone(true).attr("id", "location" + locationsCount).find(':input').each(function(){
            //set id to store the updated section number
            var newId = this.id + locationsCount;
            //update for label
            $(this).prev().attr('for', newId);
            //update id
            this.id = newId;
        }).end()
        // print new legend
        .find('legend').each(function(){
            var newLocation = locationsCount + 1;
            var newLegend = $(this).text() + ' - Room ' + newLocation;
            $(this).html( newLegend );
        }).end()
        //inject new area
        .appendTo('.bodyLocation');
        //increment
        locationsCount++;

        buttons();
        return false;
    };

    //remove location
    function removeLocation() {
        //fade out section
        $(this).parent().fadeOut(300, function(){
            //remove parent element (main section)
            $(this).parent().remove();
            return false;
        });
        locationsCount--;
        
        buttons();
                // time out before fadeOut finishes
        setTimeout(function(){
            priceChange();
            deviceChange();
        }, 350);
        
        return false;
    };

    // sticky message
    $(window).scroll(function(){
        if ($(window).scrollTop() >= $top) {
            $('#message').addClass('position-fixed bg-info');
        } else {
            $('#message').removeClass('position-fixed bg-info');
        }
    });
    

});

