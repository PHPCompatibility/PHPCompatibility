<?php

class LateStaticAttribute {
    const VALUE = 1;
    #[Example(static function () {
        return static::VALUE; // @clean
    })]
    public function decorated() {}

    public function outside() {
        return static::VALUE; // @outside
    }
}

#[Example(static::VALUE)] // @invalid
function invalidLateStaticAttribute() {}
