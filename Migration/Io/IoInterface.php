<?php
namespace Migration\Io;

/*
 * class IoInterface
 */

interface IoInterface {


    /*
     * __construct()
     *
     * @param string $base_folder the path to a project
     * @return void
     */

    public function __construct($base_folder);

    //----------------------------------------------------------------------

    /**
     * Builds a Path inside the project
     *
     * @param array $folders array of subfolders to join together
     * @return string built path
     */

    public function path($folders = null);



    //----------------------------------------------------------------------


    /**
     * Loads a file from the path
     *
     *  @param string $name filename
     *  @param mixed $folders array of folders to append
     *  @param boolean $object true to return SplFileInfo;
     *  @return SplFileInfo | string
     *  @access public
     */
    public function load($name,$folders,$object = false);

    //  -------------------------------------------------------------------------


    /**
      *  function mkdir
      *
      *  create a directory relative to the base dir
      *
      *  @access public
      *  @return boolean
      *  @throws DirectoryExistsException
      */
    public function mkdir($name);


    //----------------------------------------------------------------------

    /**
      * Check if a config is found using the supplied name
      *
      * @param string $name the config name
      * @param mixed $folders extra folder to append to path
      * @return boolean true if found false otherwise
      */

    public function exists($name,$folders = null);


    //----------------------------------------------------------------------


    /**
      * Writes a file to the given path
      *
      *  @param string $filename the filename
      *  @param array $folders the file path relative to project/self::DIR/
      *  @param string $content the file content
      *  @param boolean $overrite defaults to false
      *  @return boolean true if file written
      */
    public function write($filename,$folders,$content,$overrite = FALSE);


    //----------------------------------------------------------------------

    /**
     * Build a file/directory Iterator
     *
     * @param string $subfolder The subfolder to pass to the finder.
     * @return Symfony\Component\Finder\Finder;
     */

    public function iterator($path = NULL);


    //----------------------------------------------------------------------


    /**
     * Loads a file from the path
     *
     *  @param string $name filename
     *  @param mixed folders array of folders to append
     *  @param mixed
     */

    public function contents($name, $folders = null);



    //  -------------------------------------------------------------------------


    /**
     * Returns the base project path
     *
     * @return string project path
     */
    public function getBase();


    /**
     * Sets the project path
     *
     * @param string $path the project path
     */
    public function setBase($path);


    //---------------------------------------------------------------------

}
/* End of File */
