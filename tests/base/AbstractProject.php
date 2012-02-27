<?php
use Migration\Project;

class AbstractProject extends PHPUnit_Framework_TestCase
{


    protected $backupGlobalsBlacklist = array('project');


    protected $migration_dir = 'myproject';

    
    

    public function getProject()
    {
        #project normally injected into application. but for testing its a global variable
       global $project;

        //$this->assertInstanceOf('\Migration\Project',$project);

        # set the project folder
        //$project->getPath()->parse(__DIR__ .'myproject');

        return $project;
    }



    //  -------------------------------------------------------------------------
    # Helper Functions

     /**
      *  function  recursiveRemoveDirectory
      *
      *  @param string absolute path
      *  @param boolean true to empty directory only defaults to false
      *  @access public
      *  @source http://lixlpixel.org/recursive_function/php/recursive_directory_delete/
      */
    public static function recursiveRemoveDirectory($directory, $empty=FALSE)
    {
            // if the path has a slash at the end we remove it here
            if(substr($directory,-1) == '/') {
                    $directory = substr($directory,0,-1);
            }

            // if the path is not valid or is not a directory ...
            if(!file_exists($directory) || !is_dir($directory)) {
                    // ... we return false and exit the function
                    return FALSE;

            // ... if the path is not readable
            } elseif(!is_readable($directory)) {
                    // ... we return false and exit the function
                    return FALSE;

            // ... else if the path is readable
            } else {

                    // we open the directory
                    $handle = opendir($directory);

                    // and scan through the items inside
                    while (FALSE !== ($item = readdir($handle))) {
                            // if the filepointer is not the current directory
                            // or the parent directory
                            if($item != '.' && $item != '..') {
                                    // we build the new path to delete
                                    $path = $directory.'/'.$item;

                                    // if the new path is a directory
                                    if(is_dir($path)) {
                                            // we call this function with the new path
                                            self::recursiveRemoveDirectory($path);

                                    // if the new path is a file
                                    } else{
                                            // we remove the file
                                            unlink($path);
                                    }
                            }
                    }
                    // close the directory
                    closedir($handle);

                    // if the option to empty is not set to true
                    if($empty == FALSE) {
                            // try to delete the now empty directory
                            if(!rmdir($directory)) {
                                    // return false if not possible
                                    return FALSE;
                            }
                    }
                    // return success
                    return TRUE;
            }
    }

    // ------------------------------------------------------------

}

/* End of File */
