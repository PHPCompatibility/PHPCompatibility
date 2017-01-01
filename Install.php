<?php 

/**
 * Install functions
 * 
 * Used through vandor/bin/phpcompat_(en|dis)able
 *
 */

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
        $installed_paths = self::get_installed_path();
        if (in_array(__DIR__, $installed_paths)) {
            echo "Our path is already registered in PHP CodeSniffer\n";
        } else {
            array_push($installed_paths, __DIR__);
            self::set_installed_path($installed_paths);
            echo "Registered our path in PHP CodeSniffer\n";
        }
    }
    
    static function unregister_from_cs() {
        $installed_paths = self::get_installed_path();
        if (! in_array(__DIR__, $installed_paths)) {
            echo "Our path is not registered in PHP CodeSniffer\n";
        } else {
            $installed_paths = array_filter($installed_paths, function ($v) {
                return $v != __DIR__;
            });
            self::set_installed_path($installed_paths);
            echo "Unregistered our path in PHP CodeSniffer\n";
        }
    }

    static function make_copy() {
        $srcDir = __DIR__;
        $copy = __DIR__ .DIRECTORY_SEPARATOR.'PHPCompatibility';
        
        if ( file_exists ($copy)) {
            echo "Copy workaround is already in place\n";
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
        echo "Created copy workaround\n";
    }    
    
    static function remove_copy() {
        $copy = __DIR__ .DIRECTORY_SEPARATOR.'PHPCompatibility';
        
        if ( ! file_exists ($copy)) {
            echo "No copy workaround to remove\n";
            return;
        }
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $copy = str_replace('/', DIRECTORY_SEPARATOR, $copy);
            passthru('rmdir /S /Q "'.$copy.'"');
        } else {
            passthru('rm -rf "'.$copy.'"');
        }
        echo "Copy workaround removed\n";
    }

    static function get_installed_path() {
        $installed_paths = PHP_CodeSniffer::getConfigData('installed_paths');
        if ( $installed_paths === NULL or strlen($installed_paths) == 0 ) {
            // Because: explode(',' , NULL) == array('')
            // and we assert no data is empty array
            return array();
        }
        return explode(',', $installed_paths);
    }

    static function set_installed_path($array) {
        if(count($array) == 0) {
            PHP_CodeSniffer::setConfigData('installed_paths', NULL);
        } else {
            PHP_CodeSniffer::setConfigData('installed_paths', implode(',', $array));
        }
    }
}
