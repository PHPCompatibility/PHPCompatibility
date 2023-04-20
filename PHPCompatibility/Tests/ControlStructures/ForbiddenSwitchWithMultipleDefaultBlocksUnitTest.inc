<?php

switch ($something) {
    case 1:
        break;
    default:
        break;
    case 2:
        break;
    default:
        break;
}

switch ($something) {
    case 1:
        break;
    default:
        break;
    case 2:
        break;
}

switch ($something) {
    case 1:
        break;
    case 2:
        break;
    default:
        break;
}


switch ($foo) {
    case 'a':
        switch ($bar) {
            case 'b':
                echo 'b';
                break;
            default:
                echo 'x';
        }
        break;
    default:
        echo "x\n";
}

switch ($something) {
    case 1:
    default:
        break;
    case 2:
    default:
        break;
}

switch ($something) {
    case 1:
        break;
    case 2:
        break;
    default:
    default:
        break;
}

// Safeguard handling of switch statements using alternative syntax.
switch ($something):
    case 1:
        break;
    case 2:
        break;
    default:
    default:
        break;
endswitch;

// Safeguard handling of case/default statements with semi-colon instead of colon.
switch ($something) {
    default;
        break;
    case 1;
        break;
    case 2;
        break;
    default;
        break;
}

// Safeguard handling of case/default statements with curly braces after colon.
switch ($something) {
    case 1: {
        break;
    }
    default: {
        break;
    }
    case 2: {
        break;
    }
    default: {
        break;
    }
}

// Safeguard handling of case/default statements with mixed syntaxes.
switch ($something) {
    case 1: {
        echo 'something';
    }
    default:
        break;
    case 2;
        continue;
    default: {
        break;
    }
}

// Safeguard handling when there is a nested PHP 8.0 match structure with default.
switch ($something) {
    case 1:
        break;
    case 2:
        $foo = match($a) {
            'a'     => $a * 10,
            default => $a * 1,
        };
        break;
    default:
        break;
}

// Don't throw errors on live code review.
switch ($something) {
