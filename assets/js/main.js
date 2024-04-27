jQuery(document).ready(function ($) {
    $('#doz-form').on('submit', function (event) {
        event.preventDefault();
        $('#doz-submit').val('Calculating...');
        $('#doz-result').html(' ');
        let weight_kg = $('#weight-kg').val();
        let doz = $('#doz').val();
        var shekls = document.getElementsByName('shekl');
        for (var i = 0, length = shekls.length; i < length; i++) {
            if (shekls[i].checked) {
                // do whatever you want with the checked radio
                var shekl = shekls[i].value;

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            dataType: 'json',
            data: {
                action: 'doz_calc',
                weight_kg: weight_kg,
                shekl: shekl,
                doz: doz
            },
            success: function (response) {
                $('#doz-result').html(response.html);
                $('#doz-submit').val('Find Dose');
            },
            error: function (error) {
            }
        });
    });

    $('#roshd-form').on('submit', function (event) {
        event.preventDefault();
        $('#roshd-submit').val('Calculating ...');
        $('#roshd-result').html(' ');
        let born = $("input[name=born]").val();
        let father_height = $("input[name=father-height]").val();
        let mother_height = $("input[name=mother-height]").val();
        let height = $("input[name=height]").val();
        let weight = $("input[name=weight]").val();
        let meas_date = $("input[name=meas-date]").val();
        let height_past = $("input[name=height-past]").val();
        let past_meas_date = $("input[name=past-meas-date]").val();
        var genders = document.getElementsByName('gender');
        for (var i = 0, length = genders.length; i < length; i++) {
            if (genders[i].checked) {
                // do whatever you want with the checked radio
                var gender = genders[i].value;

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            dataType: 'json',
            data: {
                action: 'roshd_calc',
                born: born,
                father_height: father_height,
                mother_height: mother_height,
                height: height,
                weight: weight,
                meas_date: meas_date,
                height_past: height_past,
                past_meas_date: past_meas_date,
                gender: gender
            },
            success: function (response) {
                $('#roshd-result').html(response.html);
                $('#roshd-submit').val('Find Dose');
            },
            error: function (error) {
            }
        });
    });
});