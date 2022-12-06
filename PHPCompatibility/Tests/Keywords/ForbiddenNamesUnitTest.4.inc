<?php

final class Bongo {
    public const ENUM = [1, 2, 3, 4, 5];

    public static function conga(): void
    {
        foreach (self::ENUM as $key => $value) {
        }

        self::ENUM or \true;
        self::ENUM and \true;
        self::ENUM xor \false;
    }
}
