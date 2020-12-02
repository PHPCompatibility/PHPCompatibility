<?php

// OK.
$proc = proc_open(
        '/usr/bin/passwd ' . escapeshellarg($username),
        $descriptorspec,
        $pipes
);
$proc = proc_open('tail -F /var/log/nginx/stats.access.log', $descriptorspec, $pipes);
$proc = proc_open('php ' . $abs_path, $spec, $pipes, null, $_ENV);

// Undetermined. Ignore.
$proc = proc_open($command, $descriptorSpec, $pipes);
$proc = proc_open(escapeshellarg($scriptFile), $descriptorspec, $pipes, $wd);
$proc = proc_open(self::COMMAND, $descriptorSpec, $pipes);

// PHP 7.4: passing $cmd as an array.
$proc = proc_open(['php', '-r', 'echo "Hello World\n";'], $descriptors, $pipes);
$proc = proc_open(
    array(
        'php',
        '-r',
        'echo "Hello World\n";',
    ), // Not using escapeshellarg() as PHP will do the escaping.
    $descriptors,
    $pipes
);

// PHP 7.4: Warning. PHP will automatically escape arguments when $cmd is passed as an array.
$proc = proc_open(['php', '-r', escapeshellarg($echo)], $descriptors, $pipes);
$proc = proc_open(
    array(
        'phpcs',
        '--standard=' . escapeshellarg($standard), // Escaping.
        './path/to/' . escapeshellarg($file),
    ),
    $descriptors,
    $pipes
);

// Safeguard support for PHP 8 named parameters.
$proc = proc_open(
    descriptor_spec: array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("file", "/tmp/error-output.txt", "a"),
    ),
    command: '"findstr" "search" "filename.txt"',
    pipes: $pipes,
    env_vars: $env_vars
); // OK.

$proc = proc_open(
    pipes: $pipes,
    env_vars: $env_vars,
    descriptor_spec: array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("file", "/tmp/error-output.txt", "a"),
    ),
    command: array('php', '-r', escapeshellarg($echo)),
); // Error + Warning.
