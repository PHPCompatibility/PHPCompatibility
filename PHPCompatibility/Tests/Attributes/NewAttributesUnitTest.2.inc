<?php

// This code will cause a tokenizer error in PHP < 8.0. This must be the last test in the file!
#[DeprecationReason('reason: <https://some-website/reason?>')
] function attribute_containing_text_looking_like_close_tag() {}
const APP_SECRET = 'app-secret';
echo "Test\n";

#[DeprecationReason(
    'reason: <https://some-website/reason?>'
)]
function attribute_containing_mulitline_text_looking_like_close_tag() {}
