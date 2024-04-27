<?php

//Convert Age From Year To Day And Month
date_default_timezone_set( 'Asia/Tehran' );
function timeDiff_Month( $time2, $time1 ) {
	$diff = strtotime( $time2 ) - strtotime( $time1 );
	if ( $diff > 2628000 ) {
		return round( $diff / 2628000, 1 );
	} else {
		return 'تاریخ وارد شده اشتباه است';
	}
}

function timeDiff_Day( $time2, $time1 ) {
	$diff = strtotime( $time2 ) - strtotime( $time1 );
	if ( $diff > 86400 ) {
		return round( $diff / 86400, 1 );
	} else {
		return 'تاریخ وارد شده اشتباه است';
	}
}

//Check HSDS Unavailable Numbers
function Check_HSDS_Unavailable_Numbers( $num ) {
	$GLOBALS['HSDS'];

	$num_floor = floor( $num );
	$num_round = round( $num );
	$num_ashar = $num - floor( $num );

	if ( $num_ashar < 0.5 ) {
		$num1 = $num_floor;
		$num2 = $num_floor + 0.5;

		foreach ( $GLOBALS['HSDS'] as $value ) {
			if ( $value['Age'] == $num1 ) {
				$num1_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
			}
			if ( $value['Age'] == $num2 ) {
				$num2_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
				$result      = array(
					"age1"        => $num1*30.5,
					"age2"        => $num2*30.5,
					"age1_values" => $num1_values,
					"age2_values" => $num2_values,
				);

				return $result;
			}

		}
	} elseif ( $num_ashar > 0.5 ) {
		$num2 = $num_round;
		$num1 = $num_round - 0.5;

		foreach ( $GLOBALS['HSDS'] as $value ) {
			if ( $value['Age'] == $num1 ) {
				$num1_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
			}
			if ( $value['Age'] == $num2 ) {
				$num2_values = array(
					"Mean" => $value['Mean'],
					"SD"   => $value['SD']
				);
				$result      = array(
					"age1"        => $num1*30.5,
					"age2"        => $num2*30.5,
					"age1_values" => $num1_values,
					"age2_values" => $num2_values,
				);

				return $result;
			}
		}
	}
}

//Check HVSDS Unavailable Numbers
function Check_HVSDS_Unavailable_Numbers( $num ) {
	$GLOBALS['HVSDS'];

	foreach ( $GLOBALS['HVSDS'] as $value ) {
		if ( $value['Age'] < $num ) {
			$num1        = $value['Age'];
			$num1_values = array(
				"Mean" => $value['Mean'],
				"SD"   => $value['SD']
			);
		} elseif ( $value['Age'] > $num ) {
			$num2        = $value['Age'];
			$num2_values = array(
				"Mean" => $value['Mean'],
				"SD"   => $value['SD']
			);
			break;
		}
	}

	$result = array(
		"age1"        => $num1*30,
		"age2"        => $num2*30,
		"age1_values" => $num1_values,
		"age2_values" => $num2_values,
	);

	return $result;
}

//Convert Persian To English
function convertPersianToEnglish( $string ) {
	$persian = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];
	$english = range( 0, 9 );

	$output = str_replace( $persian, $english, $string );

	return $output;
}