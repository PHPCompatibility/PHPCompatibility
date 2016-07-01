<?php

mktime(1, 2, 3, 4, 5, 6, true);

gmmktime(1, 2, 3, 4, 5, 6, true);

// Added after Github issue #114
$stHour = date('H');
$arrStDt = array(date('m'), date('d'), date('Y'));
echo "\n".$case_1 = mktime($stHour, 0, 0, $arrStDt[0], $arrStDt[1], $arrStDt[2]);
echo "\n".$case_2 = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
echo "\n".$case_3 = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y') + 1);
echo "\n".$case_4 = mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'));
echo "\n".$case_5 = mktime(date('H'), 0, 0, date('m'), date('d'), date('Y'));
echo "\n".$case_6 = mktime(0, 0, date('s'), date('m'), date('d'), date('Y'));

mktime(some_call(5, 1), another(1), why(5, 1, 2), 4, 5, 6);
