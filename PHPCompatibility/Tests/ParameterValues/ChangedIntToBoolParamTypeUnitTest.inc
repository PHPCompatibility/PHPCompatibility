<?php

// OK.
sem_get( $key, $max_acquire, $perm, );
sem_get($SHM_KEY, 1024, 0644 | IPC_CREAT);
sem_get( $SEMKey, 2, 0666, true);
sem_get( $SEMKey, 2, 0666, false);
ob_implicit_flush();
ob_implicit_flush(true);

// Ignore as undetermined.
sem_get( $key, $max_acquire, $perm, $auto_release );
sem_get( $key, $max_acquire, $perm, SEMVER_RELEASE );
sem_get( $key, $max_acquire, $perm, self::RELEASE_TOGGLE );
ob_implicit_flush($flag);

// Ignore for complexity + could be valid cross-version toggle.
sem_get( $key, $max_acquire, $perm, $test ? 1 : true );

// Error.
sem_get( $key, $max_acquire, $perm, 0 );
sem_get( $key, $max_acquire, $perm, 1 );
sem_get( $key, $max_acquire, $perm, -1 );
Sem_Get( $key, $max_acquire, $perm, 0 );
sem_get( $key, $max_acquire, $perm, 1.0 );
sem_get( $key, $max_acquire, $perm, 1 * 1);
ob_implicit_flush(0);
