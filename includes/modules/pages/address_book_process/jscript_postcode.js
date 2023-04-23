/**
 * @requires jquery.powertip.min.js and jQuery 3.6+
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: pilou2 Apr 11 Modified in v1.5.8a $
 */
$.when(
    $.getScript( "includes/templates/responsive_classic/jscript/jquery.powertip.min.js" ),
    $.Deferred(function( deferred ){
        $( deferred.resolve );
    })
).done(function(){

	$('#postcode').on('keyup change', function() {
		var cleancode = $('#postcode').val().replace(/[-|―|ー]/g,'');
		if (cleancode.length >= 5) {
			var donnees = 'country=' + $('#country').val() + '&postcode=' + cleancode;
			zcJS.ajax({
				url: "ajax.php?act=AjaxPostcodeQuery&method=postcodequery",
				data: donnees
			}).done(function( response ) {
				let optionsv = '';
				let optionss = '';
				let preced_cit = 'none';
				let preced_str = 'none';
				let details = [];
				let countcities = 0;
				let countstreet = 0;
				let reg = /[1-9]/;

				if (response.length === 0) {
					return;
				}
				if (reg.test(response[0].zone_id)) {
					$('#stateZone').val(response[0].zone_id);
				} else {
					$('#state').val(response[0].zone_id);
				}
				$('#city').val(response[0].city);
				$('#street-address').val(response[0].street_name);
				if (response.length > 1) {
					for (let i = 0; i < response.length; ++i) {
						if (response[i].city.length > 0 && response[i].city != preced_cit) {
							if (response[i].city.length > 0) {
								optionsv += '<p onclick="document.getElementById(\'city\').value = \'' + response[i].city + '\'">' + response[i].city + '</p>';
							}
							preced_cit = response[i].city;
							countcities++;
						}
						if (response[i].street_name != preced_str) {
							if (response[i].street_name.length > 0) {
								optionss += '<p onclick="document.getElementById(\'street-address\').value = \'' + response[i].street_name + '\'">' + response[i].street_name + '</p>';
							}
							preced_str = response[i].street_name;
							countstreet++;
						}
					}
					optionsv = (countcities > 1) ? optionsv : [] ;
					optionss = (countstreet > 0) ? optionss : [] ;
				}
				$(function() {
					var mouseOndiv = $('#city');
					var tipContent = $(optionsv);
					mouseOndiv.data('powertipjq', tipContent);
					mouseOndiv.powerTip({
						placement: 'e',
						mouseOnToPopup: true
					});
				});
				$(function() {
					var mouseOndiv = $('#street-address');
					var tipContent = $(optionss);
					mouseOndiv.data('powertipjq', tipContent);
					mouseOndiv.powerTip({
						placement: 'e',
						mouseOnToPopup: true
					});
				});
			});
		}
	});

});
