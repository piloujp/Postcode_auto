<?= "<script>
$.when(
    $.getScript( \"" . $this->zcPluginCatalogPath . "includes/templates/default/jscript/jquery.powertip.min.js\" ),
    $.Deferred(function( deferred ){
        $( deferred.resolve );
    })
).done(function(){

    $('#postcode-bill').on('keyup change', function() {
        var cleancode = $('#postcode-bill').val().replace(/[-|―|ー]/g,'');
        if (cleancode.length >= 4 && /^\d+$/.test($('#country-bill').val())) {
            var donnees = 'country=' + $('#country-bill').val() + '&postcode=' + cleancode;
            zcJS.ajax({
                url: \"ajax.php?act=AjaxPostcodeQuery&method=postcodequery\",
                data: donnees
            }).done(function( response ) {
                let optionsv = '';
                let optionss = '';
                let preced_cit = 'none';
                let preced_str = 'none';
                let countcities = 0;
                let countstreet = 0;
                let reg = /^\d*[1-9]\d*$/;

                if (response.length === 0) {
                    return;
                }
                if (reg.test(response[0].zone_id)) {
                    $('#stateZone-bill').val(response[0].zone_id);
                } else {
                    $('#state-bill').val(response[0].zone_id);
                }
                $('#city-bill').val(response[0].city);
                if (response[0].street_name.length > 0) {
                    $('#street-address-bill').val(response[0].street_name);
                }
                if (response.length > 1) {
                    for (let i = 0; i < response.length; ++i) {
                        if (response[i].city != preced_cit) {
                            if (response[i].city.length > 0) {
                                cityfield = document.getElementById('city-bill');
                                optionsv += '<p onclick=\"cityfield.value = \'' + response[i].city.replace(/'/g, '\\\\\'') + '\';chevent = new Event(\'change\', {bubbles: true});cityfield.dispatchEvent(chevent);\">' + response[i].city + '</p>';
                            }
                            preced_cit = response[i].city;
                            countcities++;
                        }
                        if (response[i].street_name != preced_str) {
                            if (response[i].street_name.length > 0) {
                                optionss += '<p onclick=\"document.getElementById(\'street-address-bill\').value = \'' + response[i].street_name.replace(/'/g, '\\\\\'') + '\'\">' + response[i].street_name + '</p>';
                            }
                            preced_str = response[i].street_name;
                            countstreet++;
                        }
                    }
                    optionsv = (countcities > 1) ? optionsv : [] ;
                    optionss = (countstreet > 0) ? optionss : [] ;
                }
                $(function() {
                    var mouseOndiv = $('#city-bill');
                    var tipContent = $(optionsv);
                    mouseOndiv.data('powertipjq', tipContent);
                    mouseOndiv.powerTip({
                        placement: 'e',
                        mouseOnToPopup: true
                    });
                });
                $(function() {
                    var mouseOndiv = $('#street-address-bill');
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

    $('#city-bill').on('keyup change', function() {
        if ($('#city-bill').val().length > 1) {
            var cleancode = $('#postcode-bill').val().replace(/[-|―|ー]/g,'');
            if (cleancode.length >= 4 && /^\d+$/.test($('#country-bill').val())) {
                var citydata = 'country=' + $('#country-bill').val() + '&postcode=' + cleancode + '&city=' + $('#city-bill').val();
                zcJS.ajax({
                    url: \"ajax.php?act=AjaxPostcodeQuery&method=postcodequery\",
                    data: citydata
                }).done(function( resp ) {
                    let optionss = '';
                    let preced_str = 'none';
                    let countstreet = 0;
                    let reg = /^\d*[1-9]\d*$/;

                    if (resp.length === 0) {
                        return;
                    }
                    if (reg.test(resp[0].zone_id)) {
                        $('#stateZone-bill').val(resp[0].zone_id);
                    } else {
                        $('#state-bill').val(resp[0].zone_id);
                    }
                    if ($('#street-address-bill').val() == '' || resp[0].street_name != '') {
                        $('#street-address-bill').val(resp[0].street_name);
                    }
                    if (resp.length > 1) {
                        for (let i = 0; i < resp.length; ++i) {
                            if (resp[i].street_name != preced_str) {
                                if (resp[i].street_name.length > 0 && $('#city-bill').val() === resp[i].city) {
                                    optionss += '<p onclick=\"document.getElementById(\'street-address-bill\').value = \'' + resp[i].street_name.replace(/'/g, '\\\\\'') + '\'\">' + resp[i].street_name + '</p>';
                                }
                                preced_str = resp[i].street_name;
                                countstreet++;
                            }
                        }
                        optionss = (countstreet > 0) ? optionss : [] ;
                    }
                    $(function() {
                        var mouseOndiv = $('#street-address-bill');
                        var tipContent = $(optionss);
                        mouseOndiv.data('powertipjq', tipContent);
                        mouseOndiv.powerTip({
                            placement: 'e',
                            mouseOnToPopup: true
                        });
                    });
                });
            }
        }
    });

    $('#postcode-ship').on('keyup change', function() {
        var cleancode = $('#postcode-ship').val().replace(/[-|―|ー]/g,'');
        if (cleancode.length >= 4 && /^\d+$/.test($('#country-ship').val())) {
            var donnees = 'country=' + $('#country-ship').val() + '&postcode=' + cleancode;
            zcJS.ajax({
                url: \"ajax.php?act=AjaxPostcodeQuery&method=postcodequery\",
                data: donnees
            }).done(function( response ) {
                let optionsv = '';
                let optionss = '';
                let preced_cit = 'none';
                let preced_str = 'none';
                let countcities = 0;
                let countstreet = 0;
                let reg = /^\d*[1-9]\d*$/;

                if (response.length === 0) {
                    return;
                }
                if (reg.test(response[0].zone_id)) {
                    $('#stateZone-ship').val(response[0].zone_id);
                } else {
                    $('#state-ship').val(response[0].zone_id);
                }
                $('#city-ship').val(response[0].city);
                if (response[0].street_name.length > 0) {
                    $('#street-address-ship').val(response[0].street_name);
                }
                if (response.length > 1) {
                    for (let i = 0; i < response.length; ++i) {
                        if (response[i].city != preced_cit) {
                            if (response[i].city.length > 0) {
                                cityfield = document.getElementById('city-ship');
                                optionsv += '<p onclick=\"cityfield.value = \'' + response[i].city.replace(/'/g, '\\\\\'') + '\';chevent = new Event(\'change\', {bubbles: true});cityfield.dispatchEvent(chevent);\">' + response[i].city + '</p>';
                            }
                            preced_cit = response[i].city;
                            countcities++;
                        }
                        if (response[i].street_name != preced_str) {
                            if (response[i].street_name.length > 0) {
                                optionss += '<p onclick=\"document.getElementById(\'street-address-ship\').value = \'' + response[i].street_name.replace(/'/g, '\\\\\'') + '\'\">' + response[i].street_name + '</p>';
                            }
                            preced_str = response[i].street_name;
                            countstreet++;
                        }
                    }
                    optionsv = (countcities > 1) ? optionsv : [] ;
                    optionss = (countstreet > 0) ? optionss : [] ;
                }
                $(function() {
                    var mouseOndiv = $('#city-ship');
                    var tipContent = $(optionsv);
                    mouseOndiv.data('powertipjq', tipContent);
                    mouseOndiv.powerTip({
                        placement: 'e',
                        mouseOnToPopup: true
                    });
                });
                $(function() {
                    var mouseOndiv = $('#street-address-ship');
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

    $('#city-ship').on('keyup change', function() {
        if ($('#city-ship').val().length > 1) {
            var cleancode = $('#postcode-ship').val().replace(/[-|―|ー]/g,'');
            if (cleancode.length >= 4 && /^\d+$/.test($('#country-ship').val())) {
                var citydata = 'country=' + $('#country-ship').val() + '&postcode=' + cleancode + '&city=' + $('#city-ship').val();
                zcJS.ajax({
                    url: \"ajax.php?act=AjaxPostcodeQuery&method=postcodequery\",
                    data: citydata
                }).done(function( resp ) {
                    let optionss = '';
                    let preced_str = 'none';
                    let countstreet = 0;
                    let reg = /^\d*[1-9]\d*$/;

                    if (resp.length === 0) {
                        return;
                    }
                    if (reg.test(resp[0].zone_id)) {
                        $('#stateZone-ship').val(resp[0].zone_id);
                    } else {
                        $('#state-ship').val(resp[0].zone_id);
                    }
                    if ($('#street-address-ship').val() == '' || resp[0].street_name != '') {
                        $('#street-address-ship').val(resp[0].street_name);
                    }
                    if (resp.length > 1) {
                        for (let i = 0; i < resp.length; ++i) {
                            if (resp[i].street_name != preced_str) {
                                if (resp[i].street_name.length > 0 && $('#city-ship').val() === resp[i].city) {
                                    optionss += '<p onclick=\"document.getElementById(\'street-address-ship\').value = \'' + resp[i].street_name.replace(/'/g, '\\\\\'') + '\'\">' + resp[i].street_name + '</p>';
                                }
                                preced_str = resp[i].street_name;
                                countstreet++;
                            }
                        }
                        optionss = (countstreet > 0) ? optionss : [] ;
                    }
                    $(function() {
                        var mouseOndiv = $('#street-address-ship');
                        var tipContent = $(optionss);
                        mouseOndiv.data('powertipjq', tipContent);
                        mouseOndiv.powerTip({
                            placement: 'e',
                            mouseOnToPopup: true
                        });
                    });
                });
            }
        }
    });

});
</script>
";
