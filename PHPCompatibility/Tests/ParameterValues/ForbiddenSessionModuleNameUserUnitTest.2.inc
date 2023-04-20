<?php

/**
 * The tests involving PHP 7.3+ indented heredocs are in a separate test case file
 * as any code after an indented heredoc will be tokenizer garbage on PHP < 7.3.
 */

// Safeguard handling PHP 7.3 indented heredocs/nowdocs.
session_module_name(
    <<<"EOD"
    user-nonsense
    EOD
); // OK.

session_module_name(
    <<<'EOD'
    user
    EOD
); // Not OK.
