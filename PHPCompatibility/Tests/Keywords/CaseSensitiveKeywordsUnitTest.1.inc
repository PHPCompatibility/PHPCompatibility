<?php

class KeywordParent {
    const VALUE = 1;
}
class KeywordChild extends KeywordParent {
    #[Example(SELF::VALUE, PaReNt::VALUE)] // @clean
    public function decorated() {}

    #[Example(static function () {
        return STATIC::VALUE; // @clean
    })]
    public function closureDecorated() {}

    public function outside() {
        return SELF::VALUE; // @outside
    }
}
