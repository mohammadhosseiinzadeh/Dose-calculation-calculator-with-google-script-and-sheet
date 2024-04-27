<?php

add_shortcode( 'calc', 'OCL_Calc_Func' );
function OCL_Calc_Func() {
    include_once OCL_PART . '/calc.php';
}