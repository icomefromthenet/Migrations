<?php
# load the application autolader
require(__DIR__.'/../Migration/Bootstrap.php');

# register the test autoloader
spl_autoload_register( function ($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        
        # remove the first occurance of 'faker'
        $count = 1;
        $namespace = str_replace('Migration\\','',$namespace,$count);
        $namespace = str_replace('Tests\\','',$namespace,$count);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fileName  = __DIR__ . DIRECTORY_SEPARATOR . $fileName;
    
    if(is_file($fileName)) {
        require $fileName;    
    }
    
});

/* End of File */