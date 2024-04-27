<?php
if (isset($_POST['submit'])):

	//Inputs
	$born = $_POST['born'];
	$gender = $_POST['gender'];
	$father_height = $_POST['father-height'];
	$mother_height = $_POST['mother-height'];
	$height = $_POST['height'];
	$weight = $_POST['weight'];
	$meas_date = $_POST['meas-date'];
	$height_past = $_POST['height-past'];
	$past_meas_date = $_POST['past-meas-date'];

	//Now Date
	$meas_date_arr = explode('/', $meas_date);
	$meas_date = jalali_to_gregorian($meas_date_arr[0], $meas_date_arr[1], $meas_date_arr[2], '/');

	//Past Date
	$past_meas_date_arr = explode('/', $past_meas_date);
	$past_meas_date = jalali_to_gregorian($past_meas_date_arr[0], $past_meas_date_arr[1], $past_meas_date_arr[2], '/');

	//Age Meas
	$born_arr = explode('/', $born);
	$born_date = jalali_to_gregorian($born_arr[0], $born_arr[1], $born_arr[2], '/');
	$born_month = timeDiff_Month($meas_date, $born_date);
	$born_month_past = timeDiff_Month($past_meas_date, $born_date);
	$born_days = timeDiff_Day($meas_date, $born_date);

	//Height Date Diff
	$height_date_diff = timeDiff_Day($meas_date, $past_meas_date);


	//Loop Array Search
	if ($gender == 'male') {
		include_once OCL_INC . '/HSDS-male.php';
		include_once OCL_INC . '/HVSDS-male.php';
	} elseif ($gender == 'female') {
		include_once OCL_INC . '/HSDS-Female.php';
		include_once OCL_INC . '/HVSDS-Female.php';
	}

	//HSDS
	if ($born_month >= 24 && $born_month <= 240.5) {
		foreach ($GLOBALS['HSDS'] as $value) {
			if ($value['Age'] == $born_month) {
				$HSDS_values = array(
					"Mean" => $value['Mean'],
					"SD" => $value['SD']
				);
				break;
			}
		}

		if (!isset($HSDS_values)) {
			$result = Check_HSDS_Unavailable_Numbers($born_month);
			$SD = ((($born_days - $result['age1']) * ($result['age2_values']['SD'] - $result['age1_values']['SD'])) / ($result['age2'] - $result['age1'])) + $result['age1_values']['SD'];
			$Mean = ((($born_days - $result['age1']) * ($result['age2_values']['Mean'] - $result['age1_values']['Mean'])) / ($result['age2'] - $result['age1'])) + $result['age1_values']['Mean'];
			$HSDS_values = array(
				"Mean" => $Mean,
				"SD" => $SD
			);
		}
	} else {
		$HSDS_values = 0;
	}

	//HSDS PAST
	if ($born_month >= 24 && $born_month <= 240.5) {
		foreach ($GLOBALS['HSDS'] as $value) {
			if ($value['Age'] == $born_month_past) {
				$HSDS_values_past = array(
					"Mean" => $value['Mean'],
					"SD" => $value['SD']
				);
				break;
			}
		}

		echo '<br> past: '.$born_month_past . '<br>';

		if (!isset($HSDS_values_past)) {
			$result = Check_HSDS_Unavailable_Numbers($born_month_past);
			echo $born_month_past;
			$SD = ((($born_days - $result['age1']) * ($result['age2_values']['SD'] - $result['age1_values']['SD'])) / ($result['age2'] - $result['age1'])) + $result['age1_values']['SD'];
			$Mean = ((($born_days - $result['age1']) * ($result['age2_values']['Mean'] - $result['age1_values']['Mean'])) / ($result['age2'] - $result['age1'])) + $result['age1_values']['Mean'];

			$HSDS_values_past = array(
				"Mean" => $Mean,
				"SD" => $SD
			);

			print_r($HSDS_values_past);
		}
	} else {
		$HSDS_values_past = 0;
	}

	//HVSDS
//        if (($gender == "male" && $born_month <= 216) || ($gender == "female" && $born_month <= 192)) {
//            foreach ($GLOBALS['HVSDS'] as $value) {
//                if ($value['Age'] == $born_month) {
//                    $HVSDS_values = array(
//                        "Mean" => $value['Mean'],
//                        "SD" => $value['SD']
//                    );
//                    break;
//                }
//            }
//
//            if (!isset($HVSDS_values)) {
//                $result = Check_HVSDS_Unavailable_Numbers($born_month);
//                print_r($result);
//                $SD = ($born_days - $result['age1']) * ($result['age2_values']['SD'] - $result['age1_values']['SD']) / ($result['age2'] - $result['age1']) + $result['age1_values']['SD'];
//                $Mean = ($born_days - $result['age1']) * ($result['age2_values']['Mean'] - $result['age1_values']['Mean']) / ($result['age2'] - $result['age1']) + $result['age1_values']['Mean'];
//
//                $HVSDS_values = array(
//                    "Mean" => $Mean,
//                    "SD" => $SD
//                );
//            }
//        } else {
//            $HVSDS_values = 0;
//        }


	//Formulas
	$HSDS_now = $HSDS_values == 0 ? 'سن فرد باید بین 2 تا 20 سال باشد.' : ($height - $HSDS_values['Mean']) / $HSDS_values['SD'];
	$HSDS_past = $HSDS_values_past == 0 ? 'سن فرد باید بین 2 تا 20 سال باشد.' : ($height_past - $HSDS_values_past['Mean']) / $HSDS_values_past['SD'];
	$Growth_Rate = ($height - $height_past) * (365 / $height_date_diff);
//        $HVSDS = $HVSDS_values == 0 ? 'به‌منظور محاسبه HVSDS، سن فرد باید حداکثر ۱۶ برای دختران و حداکثر ۱۸ برای پسران باشد.' : ($Growth_Rate - $HVSDS_values['Mean']) / $HVSDS_values['SD'];
	$Target_Height = $gender == "male" ? (($father_height + $mother_height) / 2) + 6.5 : (($father_height + $mother_height) / 2) - 6.5;
	$BMI = $weight / pow($height/100, 2);
	$BSA = sqrt(($height * $weight) / 3600);
	?>
	<span class="calc_log">HSDS کنونی: <?= $HSDS_now; ?></span>
	<span class="calc_log">HSDS پیشین: <?= $HSDS_past; ?></span>
	<span class="calc_log">سرعت رشد: <?= round($Growth_Rate,2); ?></span>
	<!--        <span class="calc_log">HVSDS: --><?//= $HVSDS; ?><!--</span>-->
	<span class="calc_log">Target Height: <?= round($Target_Height,1); ?></span>
	<span class="calc_log">BMI: <?= round($BMI,1); ?></span>
	<span class="calc_log">BSA: <?= round($BSA,2); ?></span>
<?php endif; ?>