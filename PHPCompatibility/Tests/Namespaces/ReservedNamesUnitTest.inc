<?php

// OK.
namespace MyPHPApp;
namespace My\PHP\App;
namespace {
    // Do something.
}

// Warning.
namespace PHP;
namespace PHP\App;
namespace PHP\Classes {
    // Do something.
}

// Error PHP 7.4+.
namespace FFI;
namespace FFI\MyClass;

// Error PHP 8.2+.
namespace Random;
namespace Random\Generator;

// Verify the sniff handles case-insensitivety correctly.
namespace php\something; // Warning.
namespace rANdOm; // Error.

// Verify the sniff ignores use of the namespace keyword when not used for a declaration.
namespace My\namespace; // Allowed since PHP 8.0.
namespace\php\function_call(); // Use of the keyword as an operator.
echo namespace\random\SomeConstant; // Use of the keyword as an operator.

// Error PHP 8.1+.
namespace FTP;
namespace FTP\Connect;
namespace IMAP\Something;
namespace LDAP;
namespace PgSql\Sub;
namespace PSpell;

// Warning on all PHP versions.
namespace CommonMark\Sub;
namespace Componere\Sub;
namespace Gender\Sub;
namespace HRTime\Sub;
namespace MongoDB\Sub;
namespace mysql_xdevapi\Sub;
namespace parallel\Sub;
namespace Swoole\Sub;
namespace UI\Sub;
namespace wkhtmltox\Sub;
namespace XMLDiff\Sub;
namespace Aerospike\Sub;
namespace Cassandra\Sub;
namespace Couchbase\Sub;
namespace Crypto\Sub;
namespace Decimal\Sub;
namespace Grpc\Sub;
namespace HTTP\Sub;
namespace Mosquitto\Sub;
namespace pcov\Sub;
namespace pq\Sub;
namespace RdKafka\Sub;
namespace Zstd\Sub;

// Warning on PHP 7.0+.
namespace Ds\Sub;
namespace Vtiful\Kernel;
namespace Vtiful\Kernel\Sub;

// Warning on PHP 7.4+.
namespace Parle\Sub;

// Error PHP 8.4+.
namespace DBA;
namespace Odbc\Connect;
namespace Soap\Something;

// Intentional parse error. This has to be the last test in the file.
namespace PHP\Cli
