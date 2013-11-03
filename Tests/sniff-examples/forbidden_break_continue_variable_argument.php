<?php

for ($i = 0; $i < 5; $i++) {

    if ($i == 3) {
        break;
    }

    if ($i < 7) {
        continue;
    }
}

for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {

        if ($i == 3) {
            break 1;
        }

        if ($i < 7) {
            continue 1;
        }
    }
}

$num = 1;
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {

        if ($i == 3) {
            break $num;
        }

        if ($i < 7) {
            continue $num;
        }
    }
}

for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {

        if ($i == 3) {
            break rand();
        }

        if ($i < 7) {
            continue rand();
        }
    }
}


for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) {

        if ($i == 3) {
            break E_WARNING; // just some random constant
        }

        if ($i < 7) {
            continue E_WARNING;
        }
    }
}
