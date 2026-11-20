<?php

#[Example(new DateTimeImmutable('2026-01-01'))] // @clean
function classAttribute() {}

#[Example(static function () {
    return new DateTimeImmutable('2026-01-01'); // @clean
})]
function closureClassAttribute() {}

$outside = new DateTimeImmutable('2026-01-01'); // @outside

#[Example(new ValueError('message'))] // @modern
function modernClassAttribute() {}
