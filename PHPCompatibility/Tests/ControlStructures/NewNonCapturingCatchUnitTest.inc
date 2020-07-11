<?php

/**
 * Valid pre-PHP 8.0.
 */
try {
    foo();
} catch (SomeException $ex) {
    die($ex->getMessage());
} catch (ExceptionType1 | ExceptionType2 $e) {
    die($e->getMessage());
} catch (CustomException::class $e) {
   // ...
}

/**
 * Non-capturing catch - only valid in PHP 8.0+.
 */
try {
   // Some code...
} catch (ExceptionType1 | ExceptionType2) {
   // Code to handle the exception.
} catch (CustomException::class) {
   // ...
} catch ($exceptionObject::class) { // PHP 8 syntax - using ::class on objects.
   // ...
} catch (\Exception) {
   // ...
}

// Don't throw errors during live code review.
try {
   // Some code...
} catch (
