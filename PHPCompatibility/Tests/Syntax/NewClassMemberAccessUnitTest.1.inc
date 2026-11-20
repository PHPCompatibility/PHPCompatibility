<?php

#[Example(static function () {
    return (new Widget())->value; // @clean
})]
function memberAttribute() {}

function outsideMember() {
    return (new Widget())->value; // @outside
}
