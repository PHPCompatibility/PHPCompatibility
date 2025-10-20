<?php

// OK.
function YieldNotFrom() {
    yield foo();
}

function YieldFromSingleSpace() {
    yield from foo();
}

function YieldFromMultiSpace() {
    Yield           from foo();
}

function YieldFromNewLine() {
    yield
        FROM foo();
}

// PHP 8.3+
function YieldFromSingleLineWithComment() {
    yield  /*comment */ from foo();
}

function YieldFromMultiLineWithComment() {
    YIELD
    // comment.
    from foo();
}

function YieldFromMultiLineWithAnnotation() {
    yield
    // phpcs:ignore Stnd.Cat -- for reasons.
    From foo();
}
