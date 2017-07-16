<?php

// These are ok.
preg_match_all('`[a-z]+`', $subject, $matches);
stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

// These are not.
preg_match_all('`[a-z]+`', $subject);
stream_socket_enable_crypto($fp, true);
