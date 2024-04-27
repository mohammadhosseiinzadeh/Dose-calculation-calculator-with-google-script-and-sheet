<?php

include_once OCL_PATH . "tcpdf/tcpdf.php";
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->AddPage();
$pdf->SetFont('bnazanin', '', 12);
$pdf->SetTitle('عنوانی که در قسمت تایتل صفحه نشون میده');
//$doz_results = array(
//    "shekl" => 'شکل: ' . $shekl . ' میلی گرمی',
//    "daily_doz" => 'دوز روزانه (میلی گرم): ' . round($daily_doz, 2),
//    "weekly_doz" => 'دوز هفتگی (میلی گرم): ' . round($weekly_doz, 2),
//    "daily_click" => $shekl == 5 ? 'تعداد کلیک در روز: ' . $daily_click_number_5mil : 'تعداد کلیک در روز: ' . $daily_click_number_10mil,
//    "pen_time_require" => 'مدت کافی بودن قلم (روز): ' . $pen_time_require,
//    "pen_number_require" => 'تعداد قلم موردنیاز برای 30 روز: ' . $pen_number_require
//);

$html = <<<EOD
        <span class="calc_log">ddsdsdsds</span>
EOD;
$pdf->WriteHTML($html, true, 0, true, 0);
//ob_clean();
$pdf->Output();