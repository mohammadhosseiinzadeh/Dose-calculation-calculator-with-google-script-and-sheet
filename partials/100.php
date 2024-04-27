<form class="form-detail calc-right-col" method="post" id="doz-form">
    <h2>تعیین دوز سیناتروپین</h2>
    <div class="form-row">
        <label for="weight-kg">Weight (kg) </label>
        <input type="number" step="any" pattern="[-+]?[0-9]*[.,]?[0-9]+" name="weight" id="weight-kg" class="input-text"
               placeholder="وزن بیمار را با عدد مشخص کنید. (مثال: ۲۴.۶)" required>
    </div>

    <!-- <div class="form-group select-shekl">
        <div class="form-row form-row-1 ">
            <input type="radio" name="shekl" id="5gerami" class="input-text" value="5" required checked>
            <label for="5gerami"><span></span>قلم 5 میلی گرمی</label>
        </div>
        <div class="form-row form-row-1">
            <input type="radio" name="shekl" id="10gerami" class="input-text" value="10" required>
            <label for="10gerami"><span></span>قلم 10 میلی گرمی</label>
        </div>
    </div> -->

    <div class="form-row">
        <label for="doz"> Weight (kg)  </label>
        <input type="number" step="any" pattern="[-+]?[0-9]*[.,]?[0-9]+" name="doz" id="doz" class="input-text"
               required>
    </div>
    <div class="form-row-last">
        <input type="submit" name="submit" id="doz-submit" class="register" value="Find Dose">
    </div>
</form>

<div class="form-left calc-left-col">
    <h2>Appropriate dosing </h2>
    <?php





     if (isset($_POST['submit'])):
         //$weight = $_POST['weight'];
         //$shekl = $_POST['shekl'];
         //$doz = $_POST['doz'];

        //Formulas
        //$daily_doz = $weight * $doz;
        $num1 = $_POST['weight'];
        $num2 = $_POST['doz'];
        
        $url = 'https://script.google.com/macros/s/AKfycby1ieBaQspcHbXvbbTQQs35VJl-XXPw9v0223QrRsO4286X4g9WPSHbiKIsDW0mx_Yu/exec?param1='. urlencode($num1) . '&param2=' . urlencode($num2);
        $daily_doz = file_get_contents($url);


    //     $weekly_doz = $daily_doz * 7;
    //     $daily_click_number_5mil = round($daily_doz * 30);
    //     $daily_click_number_10mil = round($daily_doz * 15);

    //     if ($shekl == 5) {
    //         $pen_time_require = round(150 / $daily_click_number_5mil, 1);
    //     } elseif ($shekl == 10) {
    //         $pen_time_require = round(150 / $daily_click_number_10mil, 1);
    //     }
    //     $pen_number_require = round(30 / $pen_time_require, 1);
    //     $doz_results = array(
    //         "shekl" => 'شکل: ' . $shekl . ' میلی گرمی',
    //         "daily_doz" => 'دوز روزانه (میلی گرم): ' . round($daily_doz, 2),
    //         "weekly_doz" => 'دوز هفتگی (میلی گرم): ' . round($weekly_doz, 2),
    //         "daily_click" => $shekl == 5 ? 'تعداد کلیک در روز: ' . $daily_click_number_5mil : 'تعداد کلیک در روز: ' . $daily_click_number_10mil,
    //         "pen_time_require" => 'مدت کافی بودن قلم (روز): ' . $pen_time_require,
    //         "pen_number_require" => 'تعداد قلم موردنیاز برای 30 روز: ' . $pen_number_require
    //     );
        ?>

        
         <span class="calc_log"><?= $doz_results['daily_doz'] ?></span>

 
         <span class="calc_log"><?= $doz_results['shekl'] ?></span>
         <span class="calc_log"><?= $doz_results['weekly_doz'] ?></span>
         <span class="calc_log"><?= $doz_results['daily_click'] ?></span>
         <span class="calc_log"><?= $doz_results['pen_time_require'] ?></span>
         <span class="calc_log"><?= $doz_results['pen_number_require'] ?></span>


         <form method="post">
             <input type="submit" name="pdf-download" value="pdf download">
         </form>
    <?php endif; ?>
    <div id="doz-result"></div>
    <?php
    if (isset($_POST['pdf-download'])) {
        include_once OCL_INC . '/pdf.php';
    }
    ?>
</div>