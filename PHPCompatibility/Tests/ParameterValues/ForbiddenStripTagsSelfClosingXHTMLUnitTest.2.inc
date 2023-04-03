<?php

/**
 * The tests involving PHP 7.3+ indented heredocs are in a separate test case file
 * as any code after an indented heredoc will be tokenizer garbage on PHP < 7.3.
 */

// Safeguard handling of PHP 7.3 indented heredocs/nowdocs.
// Okay.
strip_tags($input,
    <<<'EOD'
    <script><p><div>
    EOD
);

// Not okay.
strip_tags($input, <<<'EOD'
    <meta/><input/>
    EOD
);
