<?php
/*
 * Same behaviour across PHP versions as the passed argument(s) are not touched before the function(s) are called.
 */
function parametersNotTouched($x) {
    func_get_arg(0);
    func_get_args();
    debug_backtrace();
    ++$x;
}

$d = function($a, $b, ...$c) {
    func_get_arg(0);
    func_get_args();
};

/*
 * Prevent false positives.
 */
function specialCasesA($a) {
    $a = 'foo';
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); // 'args' is ignored, so not affected.
    debug_backtrace()[0]['class']; // Explicitly requesting a non-args index from the backtrace array.
}

function notOurTarget($a) {
    $a   = 'changed!';
    $obj = new ABC();
    echo $obj->
        func_get_arg(0); // OK, not the PHP native function call.
    echo $obj?->debug_backtrace(); // OK, not the PHP native function call.
    echo ABC::func_get_args(); // OK, not the PHP native function call.
    echo \Fully\Qualified\debug_print_backtrace(); // OK, not the PHP native function call.
    const DEBUG_BACKTRACE = 'abc';
}

debug_backtrace(); // Not called within the scope of a function, so 'args' is not set/affected.

$e = function() {
    $abc = 'abc';
    var_dump(func_get_args()); // OK, no named args, so no risk.
};

function requestingNonNamedArgWhichCannotBeChanged( $a, $b ) {
    $a = 'abc';
    func_get_arg(2); // OK, requesting a non-named additional argument.
}

// No scope opener/closer.
abstract class AbstractMethodsHaveNoScopeNorCode {
    abstract protected function def();
}

interface InterfaceMethodsHaveNoScopeNorCode {
    public function def();
}

// Making sure the sniff does not get confused over nested structures.
function handledNestedClosedScopesCorrectly($a, $b, ...$c) {
    $d = function($args) {
        $a = 'abc';
        $b = \func_get_args();
    };

    function local_function($b = 123) {
        -- /* comment */ $c;
        func_get_arg(0);
    }

    class NestedClass {
        public function some_method() {
            debug_print_backtrace();
        }
    }

    func_get_args(); // OK, nothing changed in the scope we're interested in.
}

/*
 * PHP 7.0+ changed behaviour.
 */
function php70ChangedBehaviour($x) {
    $x++;
    func_get_arg(0); // Error.
    Func_Get_Args(); // Error.
    \DEBUG_BACKTRACE(); // Error.
}

$d = function($a, $b, ...$c) {
    func_get_arg(
        2
    ); // OK.
    $b = array_map('strtolower', $b);
    func_get_arg( 0 ); // OK - $a was not touched
    func_get_arg(1); // Error.
    func_get_arg(2); // OK - $c was not touched (yet).
    func_get_args(); // Error.
    $d = array_map('strtolower', $c);
    func_get_arg( 0 ); // OK - $a was not touched
    func_get_arg(1); // Error.
    func_get_arg(2); // Warning.
    func_get_args(); // Error.
    $c[0] = 'something else';
    \func_get_arg(0); // OK - $a was not touched.
    \func_get_arg(1); // Error.
    \FUNC_GET_ARG(2); // Error.
    debug_backtrace(); // Error.
    debug_print_backtrace( $index ); // Error.
    debug_backtrace ( )  [ 1 ]; // Error. Index which will be used is unknown.
    debug_backtrace ( )  [ 2 ] [ 'args' ]; // Error.
};

function assignmentToSubArray($a, $b, ...$c) {
    $a[1]['a'][2] = 'abc';

    function local_function( $b = 123 ) {
        $c = 'something';
    }

    func_get_arg(0); // Error.
    func_get_arg(1); // OK. Changed in a different scope.
    func_get_arg($var); // Error.
    $c = func_get_arg(2); // OK. Assignment operator is right associative.
}


function functionCallsMayChangeParamByReferenceA($x) {
    array_sort($x);
    \func_get_args ( ); // Warning, $x may (in this case: will) have been changed by reference.
}

function functionCallsMayChangeParamByReferenceB($x) {
    $y = \function_call($x);
    debug_backtrace(); // Warning, $x may have been changed by reference.
}

/*
 * Some more tests based on real life code.
 */
    function add_node( $args ) {
        // Shim for old method signature.
        if ( func_num_args() >= 3 && is_string( func_get_arg( 0 ) ) ) { // OK.
            $args = array_merge( array( 'parent' => func_get_arg( 0 ) ), func_get_arg( 2 ) ); // OK.
        }
    }

function author_can( $post, $capability ) {
    if ( $post === 'abc' ) {
        return false;
    }

    $args = array_slice( func_get_args(), 2 ); // OK, not accessing a named parameter.
}

    function feedback( $string, $other ) {
        if ( isset( $this->upgrader->strings[ $string ] ) ) {
            $string = $this->upgrader->strings[ $string ];
        }

        if ( strpos( $string, '%' ) !== false ) {
            $args = array_slice( func_get_args(), 0 ); // Error.
            $args = array_slice( func_get_args(), 1 ); // OK, param changed is not one of the ones targetted by array_slice.
            $args = \array_splice(
                func_get_args(),
                2
            ); // OK, not accessing a named parameter.
        }
    }

// Issue #1207.
function ignoreParamInReturn ( $stuff ) {
    if ( true === SOME_CONSTANT ) {
        return [$somethingElse['key'], 'b' => $stuff];
    }

    $args = func_get_args(); // OK.
}

function ignoreParamInExit ( $stuff ) {
    if ( true === SOME_CONSTANT ) {
        exit ( 'Did ' . functionCall() . $stuff );
    }

    $args = func_get_args(); // OK.
}

function ignoreParamInDie ( $stuff ) {
    if ( true === SOME_CONSTANT ) {
        die ( 'Did ' . $stuff );
    }

    $args = func_get_args(); // OK.
}

function ignoreParamInThrow ( $stuff, $msg ) {
    if ( true === SOME_CONSTANT ) {
        throw new Exception($msg);
    }

    $args = func_get_args(); // OK.
}

// Issue #1240 - return/exit with unscoped conditions.
function ignoreParamInReturnUnscopedCondition ( $stuff ) {
    if ( true === SOME_CONSTANT )
        return $stuff;

    $args = func_get_args(); // OK.
}

function ignoreParamInExitUnscopedCondition ( $stuff ) {
    if ( true === SOME_CONSTANT )
        do_something OR die( 'Did ' . functionCall( $stuff ) );

    $args = func_get_args(); // OK.
}

function ignore_isset($x) {
    if (isset($x[0])) {}
    func_get_args(); // OK.
}

function ignore_empty($x) {
    if (empty($x)) {}
    func_get_args ( ); // OK.
}

function do_NOT_ignore_unset($x) {
    unset($x[0]);
    \func_get_args(); // Error.
}

// Issue #1240, part 2 - plain assignments.
function ignorePlainAssignments($stuff) {
    $other = $stuff;
    $other = $stuff + 10;
    $other = 10 + $stuff;
    $args = func_get_args(); // OK.
}

function dontIgnoreDoubleAssignment($stuff) {
    $other = $stuff = $other;
    $args = func_get_args(); // Error.
}

function dontIgnoreReferenceAssignments($stuff) {
    $other = &$stuff;
    // Do some more.
    $args = func_get_args(); // Warning, reference assignment.
}

function dontIgnoreNonPlainAssignments($matches) {
    $other = preg_match('/regex/', $subject, $matches);
    // Do some more.
    $args = func_get_args(); // Warning, non-plain assignment.
}

function flagPreIncrementDecrement($x, $y) {
    --$x;
    ++$y;
    func_get_arg(0); // Error.
    \func_get_arg(1); // Error.
    Func_Get_Args(); // Error.
    \debug_backtrace(); // Error.
}

// Safeguard against false positives on namespaced function calls.
function namespacedMirrorFunctionsAreNotOurTarget($a) {
    $a = 'changed!';
    Partially\Qualified\func_get_args(); // OK.
    namespace\Relative\debug_backtrace(); // OK.
}

function hasNestedMirrorMethods() {
    class MirrorMethods {
        public function func_get_args() {} // OK.
        public &function debug_backtrace() {} // OK.
    }
}

function usingFQNconstant($a) {
    $a = 'foo';
    debug_print_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);                 // OK, 'args' is ignored, so not affected.
    debug_backtrace(namespace\Relative\DEBUG_BACKTRACE_IGNORE_ARGS);     // Error, not the global constant being passed, value unknown.
    debug_print_backtrace(\Fully\Qualified\DEBUG_BACKTRACE_IGNORE_ARGS); // Error, not the global constant being passed, value unknown.
    debug_backtrace(Partially\Qualified\DEBUG_BACKTRACE_IGNORE_ARGS);    // Error, not the global constant being passed, value unknown.
}

// Ref: https://www.php.net/manual/en/function.debug-backtrace.php#refsect1-function.debug-backtrace-parameters
function backtraceOptionsByTheNumber($a) {
    $a = 'foo';
    debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT); // Error, args index will be populated.
    debug_backtrace(0); // Error, args index will be populated.
    debug_backtrace(1); // Error, args index will be populated.
    debug_backtrace(2); // OK, args index will be omitted.
    debug_backtrace( \DEBUG_BACKTRACE_PROVIDE_OBJECT | \DEBUG_BACKTRACE_IGNORE_ARGS ); // OK, args index will be omitted.
    debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT ); // OK, args index will be omitted.
    debug_backtrace(3); // OK, args index will be omitted.

    debug_print_backtrace(0); // Error, args index will be populated.
    debug_print_backtrace(2); // OK, args index will be omitted.
}

function arrayFunctionNamesAreCaseInsensitive( $string, $other ) {
    $string = function_call([ $string ]);

    $args = ARRAY_SLICE( func_get_args(), 0 ); // Error.
    $args = \Array_Slice( func_get_args(), 1 ); // OK, param changed is not one of the ones targetted by array_slice.
    $args = Array_SPLICE(func_get_args(), 2); // OK, not accessing a named parameter.
}

// Safeguard against false negatives on method calls and namespaced function calls.
function namespacedArraySliceOrSpliceDoNotGetException( $string, $other ) {
    $string = function_call([ $string ]);

    $args = new array_slice( func_get_args(), 1 ); // Error.
    $args = MyClass::array_slice( func_get_args(), 1 ); // Error.
    $args = $obj->array_slice( func_get_args(), 1 ); // Error.
    $args = $obj?->array_slice( func_get_args(), 1 ); // Error.
    $args = namespace\array_slice( func_get_args(), 1 ); // Error.
    $args = \Fully\Qualified\array_slice( func_get_args(), 1 ); // Error.
    $args = Partially\Qualified\array_slice( func_get_args(), 1 ); // Error.

    $args = new array_splice( func_get_args(), 1 ); // Error.
    $args = MyClass::array_splice( func_get_args(), 1 ); // Error.
    $args = $obj->array_splice( func_get_args(), 1 ); // Error.
    $args = $obj?->array_splice( func_get_args(), 1 ); // Error.
    $args = namespace\Relative\array_splice( func_get_args(), 1 ); // Error.
    $args = \Fully\Qualified\array_splice( func_get_args(), 1 ); // Error.
    $args = Partially\Qualified\array_splice( func_get_args(), 1 ); // Error.
}

/*
 * Recognize list assignments correctly, including in PHP 7.1+ short lists and keyed lists.
 */
function ignoreParamAssignedToOtherVarinLongList($z) {
    list($x, list($y)) = $z;
    debug_print_backtrace(); // OK.
}

function ignoreParamAssignedToOtherVarinShortList($z) {
    [$x, [$y]] = $z;
    func_get_args(); // OK.
}

function paramIsAssignedNewValueInLongList($x) {
    list($x) = function_call();
    debug_backtrace(); // Error, $x has been assigned a new value via list.
}

function paramIsAssignedNewValueInShortList($x) {
    [$x] = function_call();
    func_get_args(); // Error, $x has been assigned a new value via short list.
}

function paramIsAssignedNewValueInNestedLongListA($y) {
    list($x, , list($y)) = function_call();
    func_get_args(); // Error, $y has been assigned a new value via list.
}

function paramIsAssignedNewValueInNestedShortListA($y) {
    [[$y], $x,] = function_call();
    debug_print_backtrace(); // Error, $y has been assigned a new value via short list.
}

function paramIsAssignedNewValueInNestedLongListB($y) {
    list(list($y), , $x, ,) = function_call();
    func_get_args(); // Error, $y has been assigned a new value via list.
}

function paramIsAssignedNewValueInNestedShortListB($y) {
    [$x, , [$y]] = function_call();
    debug_print_backtrace(); // Error, $y has been assigned a new value via short list.
}

function paramIsAssignedNewValueInLongListWithNestedList($z) {
    list(list($y), , $z, ,) = function_call();
    func_get_args(); // Error, $z has been assigned a new value via list.
}

function paramIsAssignedNewValueInShortListWithNestedList($z) {
    [[$x], $z, , ] = function_call();
    debug_print_backtrace(); // Error, $z has been assigned a new value via short list.
}

// Handle PHP 7.1+ keyed lists correctly.
function ignoreParamUsedAsKeyInLongList($x) {
    list($x => $y) = function_call();
    debug_backtrace(); // OK, key is just used to retrieve the correct value from the array.
}

function ignoreParamUsedAsKeyInShortList($x) {
    [$x => $y] = function_call();
    func_get_args(); // OK, key is just used to retrieve the correct value from the array.
}

function ignoreParamUsedAsKeyInNestedLongList($y) {
    list($x, list($y => $z)) = function_call();
    func_get_args(); // OK, key is just used to retrieve the correct value from the array.
}

function ignoreParamUsedAsKeyInNestedShortList($y) {
    [[$y => $z], $x, ] = function_call();
    debug_print_backtrace(); // OK, key is just used to retrieve the correct value from the array.
}

// Handle PHP 7.3+ list reference assignments.
function dontIgnoreReferenceAssignmentsinLongList($b) {
    list(&$d) = $b;
    // Do some more.
    func_get_args(); // Warning, reference assignment.
}

function dontIgnoreReferenceAssignmentsinShortList($b) {
    [&$d] = $b;
    // Do some more.
    debug_print_backtrace(); // Warning, reference assignment.
}

function dontIgnoreReferenceAssignmentsinNestedLongList($c) {
    list($a, list(&$b)) = $c;
    // Do some more.
    debug_backtrace(); // Warning, reference assignment.
}

function dontIgnoreReferenceAssignmentsinNestedShortList($c) {
    [$a, [&$b]] = $c;
    // Do some more.
    func_get_args(); // Warning, reference assignment.
}

// If the variable is used in the list, but not as a key and not directly assigned to, we throw a warning to err on the safe side.
function paramIsUsedInAssignmentInLongList($x) {
    list($this->property[$x]) = function_call();
    debug_backtrace(); // Warning.
}

function paramIsUsedInAssignmentInShortList($x) {
    [$this->{$x}] = function_call();
    func_get_args(); // Warning.
}

function arraySliceShouldTakeSignIntoAccount( $n0, $n1, $n2, $n3, $n4 ) {
    $n2 = function_call();

    $args = array_slice( func_get_args(), -1 ); // OK, params being accessed are unchanged.
    $args = array_slice( func_get_args(), -2 ); // OK, params being accessed are unchanged.

    $args = array_slice( func_get_args(), -3 ); // Error.
}

// Parse error on purpose (missing closing scope brace). This has to be the last test in the file.
function LiveCoding($x) {
    $x = function_call($x);
    func_get_args(); // Ignored.
