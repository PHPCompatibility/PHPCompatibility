<?php

// Flexible strings in attributes are not executable on legacy PHP versions.
#[Example(<<<ATTRIBUTE_HEREDOC
    attribute value
    ATTRIBUTE_HEREDOC)]
function indentedAttributeHeredoc() {}

#[Example(<<<'ATTRIBUTE_NOWDOC'
    attribute value
    ATTRIBUTE_NOWDOC)]
function indentedAttributeNowdoc() {}

#[Example(<<<ATTRIBUTE_INLINE
attribute value
ATTRIBUTE_INLINE)]
function attributeClosingMarkerWithCode() {}

// The same PHP 7.3 features still need diagnostics in executable code.
function ordinaryFlexibleStrings() {
    $heredoc = <<<OUTSIDE_HEREDOC
        ordinary value
        OUTSIDE_HEREDOC;
    $nowdoc = <<<'OUTSIDE_NOWDOC'
        ordinary value
        OUTSIDE_NOWDOC;
    $values = [<<<OUTSIDE_INLINE
ordinary value
OUTSIDE_INLINE];
}
