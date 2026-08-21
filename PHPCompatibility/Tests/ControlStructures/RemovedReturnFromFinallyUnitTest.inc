<?php

/*
 * OK.
 */
try {
    return doSomething();
} catch (Exception $e) {
    return 1;
} finally {
    echo 'no return statement in the finally block';
}

function yieldInFinallyDoesNotOverwriteYieldInTry() {
    try {
        yield ('hi');
    } finally {
        yield 'bye';
    }
}

function returnInsideClosedScopeShouldBeIgnoredForFunction() {
    try {
        return trim($a);
    } finally {
        function namedFunction() {
            return 'hi!';
        }
    }
}

function returnInsideClosedScopeShouldBeIgnoredForClosure() {
    try {
        return trim($a);
    } finally {
        $closure = function () {
            return 'hi!';
        };
    }
}

function returnInsideClosedScopeShouldBeIgnoredForClass() {
    try {
        return trim($a);
    } finally {
        class NamedClass {
            const ABC = 10;

            function foo() {
                return ABC;
            }
        }
    }
}

function returnInsideClosedScopeShouldBeIgnoredForAnonClass() {
    try {
        return trim($a);
    } finally {
        $anon = new class() {
            const ABC = 10;

            function foo() {
                return ABC;
            }
        };
    }
}

function returnInsideClosedScopeShouldBeIgnoredForTrait() {
    try {
        return trim($a);
    } finally {
        trait NamedTrait {
            function foo() {
                return __CLASS__;
            }
        }
    }
}

function returnInsideClosedScopeShouldBeIgnoredForEnum() {
    try {
        return trim($a);
    } finally {
        enum NamedEnum {
            function foo() {
                return __CLASS__;
            }
        }
    }
}


/*
 * PHP 8.6: return statements in a finally block are deprecated.
 */
try {
    doSomething();
} finally {
    doSomethingElse();
    $closure = function () {
        return 'hi!';
    };

    // No value.
    return;
}

function rfcExample(): array {
    try {
        return loadConfig(); // throws if the file is missing
    } finally {
        // With value.
        return []; // the exception is gone, the caller just gets []
    }
}

function multipleReturns(): array {
    try {
        return loadConfig(); // throws if the file is missing
    } finally {
        if ($a === true) {
            return []; // the exception is gone, the caller just gets []
        } else {
            return false;
        }
    }
}

// Deprecated twice: both returns are lexically inside the outer finally, even
// though one of them sits in a nested try block.
function nestedTry() {
    try {
    } finally {
        try {
            return 1;
        } finally {
            return 2;
        }
    }
}

// Deprecated once: only the closure's own finally return is flagged. The outer
// finally must not leak into the closure, so the closure's try return is left alone.
function closureWithTry() {
    try {
    } finally {
        $f = function () {
            try {
                return 1;
            } finally {
                return 2;
            }
        };
    }
}
