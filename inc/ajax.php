<?php

/* DOZ */
function do_doz_calc() {


	$num1 = $_POST['weight_kg'];
	$num2 = $_POST['doz'];
	
	$url = 'https://script.google.com/macros/s/AKfycbwYi_iIplgBGcR5WJbyeoPrnq4Jnv1UW28RIMvunskndiTFV0y59UMk7dI7OQioEJak/exec?' . '&num1=' . urlencode($num1) . '&num2=' . urlencode($num2);
	$url_doz = file_get_contents($url);
	$decoded_json = json_decode($url_doz, false);
	$daily_doz = $decoded_json->dosage;



	// $weight = $_POST['weight_kg'];
	// $shekl  = $_POST['shekl'];
	// $doz    = $_POST['doz'];

	//Formulas
	// $daily_doz                = $weight * $doz;
	// $weekly_doz               = $daily_doz * 7;
	// $daily_click_number_5mil  = round( $daily_doz * 30 );
	// $daily_click_number_10mil = round( $daily_doz * 15 );

	// if ( $shekl == 5 ) {
	// 	$pen_time_require = bcdiv( 150 / $daily_click_number_5mil, 1, 1 );
	// } elseif ( $shekl == 10 ) {
	// 	$pen_time_require = bcdiv( 150 / $daily_click_number_10mil, 1, 1 );
    //}

	// $pen_number_require = bcdiv( 30 / $pen_time_require, 1, 2 );

	// $doz_results = array(
		
		
		
	// 	"daily_doz"          => 'دوز روزانه (میلی گرم): ' . round( $daily_doz, 2 ),


	// 	// "shekl"              => ' شکل دارویی: قلم ' . $shekl .' میلی گرمی',
	// 	// "weekly_doz"         => 'دوز هفتگی (میلی گرم): ' . round( $weekly_doz, 2 ),
	// 	// "daily_click"        => $shekl == 5 ? 'تعداد کلیک در روز: ' . $daily_click_number_5mil : 'تعداد کلیک در روز: ' . $daily_click_number_10mil,
	// 	// "pen_time_require"   => 'مدت کافی بودن قلم (روز): ' . $pen_time_require,
	// 	// "pen_number_require" => 'تعداد قلم موردنیاز برای 30 روز: ' . $pen_number_require
	// );


	$output_html = '';


	$output_html .= '<span class="calc_log">' . $daily_doz . '</span>';
	//$output_html .= '<span class="calc_log">' . $doz_results['daily_doz'] . '</span>';

	// $output_html .= '<span class="calc_log">' . $doz_results['shekl'] . '</span>';
	// $output_html .= '<span class="calc_log">' . $doz_results['weekly_doz'] . '</span>';
	// $output_html .= '<span class="calc_log">' . $doz_results['daily_click'] . '</span>';
	// $output_html .= '<span class="calc_log">' . $doz_results['pen_time_require'] . '</span>';
	// $output_html .= '<span class="calc_log">' . $doz_results['pen_number_require'] . '</span>';

	$result['html'] = $output_html;

	wp_die( json_encode( $result ) );

    //wp_die( $result  );
}

add_action( 'wp_ajax_nopriv_doz_calc', 'do_doz_calc' );
add_action( 'wp_ajax_doz_calc', 'do_doz_calc' );

/* ROSHD */
function do_roshd_calc() {
//Inputs
	$born           = $_POST['born'];
	$gender         = $_POST['gender'];
	$father_height  = $_POST['father_height'];
	$mother_height  = $_POST['mother_height'];
	$height         = $_POST['height'];
	$weight         = $_POST['weight'];
	$meas_date      = $_POST['meas_date'];
	$height_past    = $_POST['height_past'];
	$past_meas_date = $_POST['past_meas_date'];

//Now Date
if (isset($meas_date) && !empty($meas_date)) {
    $meas_date_arr = explode( '/', $meas_date );
	$meas_date     = jalali_to_gregorian( $meas_date_arr[0], $meas_date_arr[1], $meas_date_arr[2], '/' );
}
	
//Past Date
if (isset($past_meas_date) && !empty($past_meas_date)) {
    $past_meas_date_arr = explode( '/', $past_meas_date );
	$past_meas_date     = jalali_to_gregorian( $past_meas_date_arr[0], $past_meas_date_arr[1], $past_meas_date_arr[2], '/' );
}
	
//Age Meas
	$born_arr             = explode( '/', $born );
	$born_date            = jalali_to_gregorian( $born_arr[0], $born_arr[1], $born_arr[2], '/' );
	$born_month           = timeDiff_Month( $meas_date, $born_date );
	$born_month_past      = timeDiff_Month( $past_meas_date, $born_date );
	$born_days            = timeDiff_Day( $meas_date, $born_date );
	$born_days_past       = timeDiff_Day( $past_meas_date, $born_date );

//Height Date Diff
	$height_date_diff = timeDiff_Day( $meas_date, $past_meas_date );


//Loop Array Search
	if ( $gender == 'male' ) {
		include_once OCL_INC . '/HSDS-Male.php';
		include_once OCL_INC . '/HVSDS-Male.php';
	} else {
		include_once OCL_INC . '/HSDS-Female.php';
		include_once OCL_INC . '/HVSDS-Female.php';
	}

//HSDS
	if ( $born_month >= 24 && $born_month <= 240.5 ) {
		foreach ( $GLOBALS['HSDS'] as $value ) {
			if ( $value['Age'] == $born_month ) {
				$HSDS_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
				break;
			}
		}

		if ( ! isset( $HSDS_values ) ) {
			$result      = Check_HSDS_Unavailable_Numbers( $born_month );
			$SD          = ( ( ( $born_days - $result['age1'] ) * ( $result['age2_values']['SD'] - $result['age1_values']['SD'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['SD'];
			$Mean        = ( ( ( $born_days - $result['age1'] ) * ( $result['age2_values']['Mean'] - $result['age1_values']['Mean'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['Mean'];
			$HSDS_values = array(
				"Mean" => $Mean,
				"SD"   => $SD
			);
		}
	} else {
		$HSDS_values = 0;
	}

//HSDS PAST
	if ( $born_month >= 24 && $born_month <= 240.5 ) {
		foreach ( $GLOBALS['HSDS'] as $value ) {
			if ( $value['Age'] == $born_month_past ) {
				$HSDS_values_past = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
				break;
			}
		}

		if ( ! isset( $HSDS_values_past ) ) {
			$result = Check_HSDS_Unavailable_Numbers( $born_month_past );
			$SD     = ( ( ( $born_days_past - $result['age1'] ) * ( $result['age2_values']['SD'] - $result['age1_values']['SD'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['SD'];
			$Mean   = ( ( ( $born_days_past - $result['age1'] ) * ( $result['age2_values']['Mean'] - $result['age1_values']['Mean'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['Mean'];

			$HSDS_values_past = array(
				"Mean" => $Mean,
				"SD"   => $SD
			);

		}
	} else {
		$HSDS_values_past = 0;
	}

//HVSDS
	if ( ( $gender == "male" && $born_month <= 216 ) || ( $gender == "female" && $born_month <= 192 ) ) {
		foreach ( $GLOBALS['HVSDS'] as $value ) {
			if ( $value['Age'] == $born_month ) {
				$HVSDS_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
				break;
			}
		}

		if ( ! isset( $HVSDS_values ) ) {
			$result = Check_HVSDS_Unavailable_Numbers( $born_month );
			$SD     = ( ( ( $born_days - $result['age1'] ) * ( $result['age2_values']['SD'] - $result['age1_values']['SD'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['SD'];
			$Mean   = ( ( ( $born_days - $result['age1'] ) * ( $result['age2_values']['Mean'] - $result['age1_values']['Mean'] ) ) / ( $result['age2'] - $result['age1'] ) ) + $result['age1_values']['Mean'];

			$HVSDS_values = array(
				"Mean" => $Mean,
				"SD"   => $SD
			);
		}
	} else {
		$HVSDS_values = 0;
	}


//Formulas
	$HSDS_now      = $HSDS_values == 0 ? 'سن فرد باید بین 2 تا 20 سال باشد.' : ( $height - $HSDS_values['Mean'] ) / $HSDS_values['SD'];
	$HSDS_past     = $HSDS_values_past == 0 ? 'سن فرد باید بین 2 تا 20 سال باشد.' : ( $height_past - $HSDS_values_past['Mean'] ) / $HSDS_values_past['SD'];
	$Growth_Rate   = ( $height - $height_past ) * ( 365 / $height_date_diff );
	$HVSDS         = $HVSDS_values == 0 ? 'به‌منظور محاسبه HVSDS، سن فرد باید حداکثر ۱۶ برای دختران و حداکثر ۱۸ برای پسران باشد.' : ( $Growth_Rate - $HVSDS_values['Mean'] ) / $HVSDS_values['SD'];
	$Target_Height = $gender == "male" ? ( ( $father_height + $mother_height ) / 2 ) + 6.5 : ( ( $father_height + $mother_height ) / 2 ) - 6.5;
	$BMI           = $weight / pow( $height / 100, 2 );
	$BSA           = sqrt( ( $height * $weight ) / 3600 );

$output_html = '';

//Current HSDS
if (
    (isset($born) && !empty($born)) &&
    (isset($gender) && !empty($gender)) &&
    (isset($height) && !empty($height)) &&
    (isset($meas_date) && !empty($meas_date)) &&
    !is_nan($HSDS)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">Current HSDS: ' . round($HSDS_now, 2) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">Current HSDS: جهت محاسبه؛ تاریخ تولد، جنسیت، قد فعلی و تاریخ اندازه گیری فعلی را تکمیل نمایید.</span>';
}

//Previous HSDS
if (
    (isset($born) && !empty($born)) &&
    (isset($gender) && !empty($gender)) &&
    (isset($height_past) && !empty($height_past)) &&
    (isset($past_meas_date) && !empty($past_meas_date)) &&
    (isset($meas_date) && !empty($meas_date)) &&
    !is_nan($HSDS_past)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">Previous HSDS: ' . round( $HSDS_past, 2) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">Previous HSDS: جهت محاسبه؛ تاریخ تولد، جنسیت، قد پیشین، تاریخ اندازه پیشین و تاریخ اندازه گیری فعلی را تکمیل نمایید.</span>';
}

//Height Velocity
if (
    (isset($height) && !empty($height)) &&
    (isset($meas_date) && !empty($meas_date)) &&
    (isset($height_past) && !empty($height_past)) &&
    (isset($past_meas_date) && !empty($past_meas_date)) &&
    !is_nan($Growth_Rate)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">Height Velocity (cm/year): ' . round( $Growth_Rate, 2 ) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">Height Velocity (cm/year): جهت محاسبه؛ قد فعلی، تاریخ اندازه گیری فعلی، قد پیشین و تاریخ اندازه پیشین را تکمیل نمایید.</span>';
}

//HVSDS
if (
    (isset($born) && !empty($born)) &&
    (isset($gender) && !empty($gender)) &&
    (isset($height) && !empty($height)) &&
    (isset($height_past) && !empty($height_past)) &&
    (isset($past_meas_date) && !empty($past_meas_date)) &&
    (isset($meas_date) && !empty($meas_date)) &&
    !is_nan($HVSDS)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">HVSDS: ' . round( $HVSDS, 2 ) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">HVSDS: جهت محاسبه؛ تاریخ تولد، جنسیت، قد فعلی، قد پیشین، تاریخ اندازه گیری پیشین و تاریخ اندازه گیری فعلی را تکمیل نمایید.</span>';
}

//Target Height
if (
    (isset($gender) && !empty($gender)) &&
    (isset($father_height) && !empty($father_height)) &&
    (isset($mother_height) && !empty($mother_height)) &&
    !is_nan($Target_Height)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">Target Height (cm): ' . round( $Target_Height, 1 ) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">Target Height (cm): جهت محاسبه؛ جنسیت، قد پدر و قد مادر را تکمیل نمایید.</span>';
}

//BMI
if (
    (isset($height) && !empty($height)) &&
    (isset($weight) && !empty($weight)) &&
    !is_nan($BMI)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">BMI (kg/m<span style="font-family:Tahoma;font-weight:300;font-size:0.6em;"><sup>2</sup></span>): ' . round( $BMI, 1 ) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">BMI (kg/m<span style="font-family:Tahoma;font-weight:300;font-size:0.6em;"><sup>2</sup></span>): جهت محاسبه؛ قد فعلی و وزن فعلی را تکمیل نمایید.</span>';
}

//BSA
if (
    (isset($height) && !empty($height)) &&
    (isset($weight) && !empty($weight)) &&
    !is_nan($BSA)
)
{
    $output_html .= '<span class="calc_log" style="text-align: left;">BSA (m<span style="font-family:Tahoma;font-weight:300;font-size:0.6em;"><sup>2</sup></span>): ' . round( $BSA, 2 ) . '</span>';
} else {
    $output_html .= '<span class="calc_log" style="text-align: right;">BSA (m<span style="font-family:Tahoma;font-weight:300;font-size:0.6em;"><sup>2</sup></span>): جهت محاسبه؛ قد فعلی و وزن فعلی را تکمیل نمایید.</span>';
}


$roshd_result['html'] = $output_html;

wp_die( json_encode( $roshd_result ) );
}

add_action( 'wp_ajax_nopriv_roshd_calc', 'do_roshd_calc' );
add_action( 'wp_ajax_roshd_calc', 'do_roshd_calc' );

