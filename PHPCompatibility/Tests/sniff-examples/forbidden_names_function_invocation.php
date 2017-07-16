<?php

// These are all keywords that were added in 5.0 or later attempted to be 
// called incorrectly as functions

abstract();
callable();
catch();
// Removed: clone(); see #284
final();
finally(); // introduced in 5.5
goto();
implements();
interface();
instanceof();
insteadof();
namespace();
private();
protected();
public();
// Removed: throw(); see #118
trait();
try();

// These are all valid uses of catch
try {}
catch
(Exception $e) {}
try {} catch(Exception $e) {}
try {
} catch (Exception $e) {}

// OK: These keywords *can* be used as function names.
bool();
int();
float();
string();
NULL();
null();
TRUE();
true();
FALSE();
false();
resource();
object();
mixed();
numeric();
