<?php
namespace Migration;

class Path
{

    /**
      *  @var $path a parsed path of the project
      */
    protected $path;

    /**
      *  function get
      *
      *  fetches the projects path
      *
      *  @return string the project path
      *  @access public
      */
    public function get()
    {
        return $this->path;
    }


    //  -------------------------------------------------------------------------


    public function __construct($path = '')
    {
         if($path === '') {
            $this->path = getcwd();
         }
        else {
            $this->path = $path;
        }

    }

    //  -------------------------------------------------------------------------


    /**
      *  function parse
      *
      *  Will parse argument given by the user and
      *  attempt to match to a realpath
      *
      *  @parma $project_folder the path given by the user
      *  @return mixed a full path or false otherwise
      *  @access public
      */
    public function parse($project_folder)
    {

        # Step 1. Check for empty path or dot operator.

        if ($project_folder === '.' || $project_folder === '') {
            // must mean use current directory
            $project_folder = rtrim(getcwd(),'/') . DIRECTORY_SEPARATOR;
        }

        # Step 2. check if path is absolute or relative

        if(strpos('../',$project_folder) == 0) {

            $project_folder =  realpath($project_folder);

        }
        elseif(is_dir($project_folder) === false) {
           #if where still false lets append cwd to what we have
           $project_folder = is_dir(rtrim(getcwd(),'/') . DIRECTORY_SEPARATOR . rtrim(ltrim($project_folder,'/'),'/'));
        }

        $this->path = $project_folder;

        return $this->path;
    }
}
/* End of File */
