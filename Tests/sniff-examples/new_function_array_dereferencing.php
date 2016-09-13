<?php

echo test()[0]; // Error.

echo test()->property[2]; // Ok.

// Issue #226 - these are all Ok.
print '"' . dol_trunc($companystatic->name, 16) . ' - ' . $val["refsuppliersologest"] . ' - ' . dol_trunc($accountingaccount->label, 32) . '"' . $sep;
if (dol_strlen(trim($this->object->address)) == 0) $this->tpl['address'] = $objsoc->address;
dol_htmloutput_errors((is_numeric($GLOBALS['error'])?'':$GLOBALS['error']),$GLOBALS['errors']);
if ((! is_numeric($records) || $records != 0) && (! isset($records['count']) || $records['count'] > 0)) {}

// Issue #227 - these should all throw an error.
echo $foo->bar()[1];
echo $foo->bar()->baz()[2];
echo testClass::another_test()[0];

// Don't throw errors during live code review.
echo test(
