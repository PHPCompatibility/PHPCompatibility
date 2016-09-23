<?php 

class PHPCompatibility_Install {
    static function enable() {
        echo "Enabling PHPCompatibility\n";
        self::make_copy();
        self::register_in_cs();
    }
    
    static function disable() {
        echo "Disabling PHPCompatibility\n";
        self::remove_copy();
        self::unregister_from_cs();
    }
    
    static function register_in_cs() {
        if (PHP_CodeSniffer::getConfigData('installed_paths') === NULL) {
            PHP_CodeSniffer::setConfigData('installed_paths', __DIR__);
            echo "Registered our path in PHP CodeSniffer\n";
        } else if (PHP_CodeSniffer::getConfigData('installed_paths') === __DIR__ ) {
            echo "Our path is already registered in PHP CodeSniffer\n";;
        } else {
            throw new Exception("Another path is registered in Code Sniffer conf :(");
        }
    }
    
    static function unregister_from_cs() {
        if (PHP_CodeSniffer::getConfigData('installed_paths') === __DIR__) {
            PHP_CodeSniffer::setConfigData('installed_paths', NULL);
            echo "Unregistered our path in PHP CodeSniffer\n";
        } else {
            echo "No path registered in Code Sniffer\n";
        }
    }

    static function make_copy() {
        $srcDir = __DIR__;
        $copy = __DIR__ .DIRECTORY_SEPARATOR.'PHPCompatibility';
        
        if ( file_exists ($copy)) {
            echo "Copy hack is already in place\n";
            return;
        }
        
        mkdir($copy);
        copy(__DIR__ .DIRECTORY_SEPARATOR.'ruleset.xml', $copy.DIRECTORY_SEPARATOR.'ruleset.xml');
        copy(__DIR__ .DIRECTORY_SEPARATOR.'Sniff.php', $copy.DIRECTORY_SEPARATOR.'Sniff.php');
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $copy = str_replace('/', DIRECTORY_SEPARATOR, $copy);
            $srcDir = str_replace('/', DIRECTORY_SEPARATOR, $srcDir);
            passthru('xcopy "'.$srcDir .DIRECTORY_SEPARATOR.'Sniffs" "'.$copy.DIRECTORY_SEPARATOR.'Sniffs" /S /E /I');
        } else {
            passthru('cp -r "'.$srcDir .DIRECTORY_SEPARATOR.'Sniffs" "'.$copy.DIRECTORY_SEPARATOR.'Sniffs"');
        }
        echo "Created copy hack\n";
    }    
    
    static function remove_copy() {
        $copy = __DIR__ .DIRECTORY_SEPARATOR.'PHPCompatibility';
        
        if ( ! file_exists ($copy)) {
            echo "No copy hack to remove\n";
            return;
        }
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $copy = str_replace('/', DIRECTORY_SEPARATOR, $copy);
            passthru('rmdir /S /Q "'.$copy.'"');
        } else {
            passthru('rm -rf "'.$copy.'"');
        }
        echo "No copy hack removed\n";
    }
    
}
