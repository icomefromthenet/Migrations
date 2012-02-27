<?php

require_once (__DIR__ .'/base/AbstractProject.php');

class PathTest extends AbstractProject
{


    public function testPathTrim() {
        $path1 = $this->path('/var/www/migrate.php/');
        $path2 = $this->path('/var/www/migrate.php');
        $path3 = $this->path('var/www/migrate.php/');
        $path4 = $this->path('var/www/migrate.php');

        $correct = 'var/www/migrate.php';

        $this->assertTrue(($path1 === $correct));
        $this->assertTrue($path2 === $correct);
        $this->assertTrue($path3 === $correct);
        $this->assertTrue($path4 === $correct);

        $path1  = 'migrate.php';
        $paths = explode('/',$path1);

        if(count($paths) === 1) {
            # just be a file name
            $filename = array_shift($paths);
        } else {
            #remove the name
            $filename = array_pop($paths);
        }

    }

    protected function path($path) {
        return ltrim(rtrim($path,'/'),'/');
    }


}
/* End of File */
