<?php

Namespace Data\Config;

Class Option_Generator implements
 \Data\Has_CacheLimit, 
 \Data\Has_OutputDir, 
 \Data\Has_ToGenerate, 
 \Data\Has_WriteCount, 
 \Data\Has_FileExtension
 \Data\Has_FilenameFormat,
 \Data\Has_Name {
    
    /**
     * Maxium number of rows to cache before writing to file
     * 
     * @var integer 
     */
    protected $cache_limit;
    
    /**
     * Format of the output name
     * 
     * Use this to add our own prefix for each schema
     * 
     * @var string (tablename_seq.extension);
     */
    protected $filename_format;
    
    /**
     * Relaitve path to the outdir
     * 
     * @var string 
     */
    protected $output_dir;
    
    /**
     * Number of rows to generate
     * 
     * @var integer 
     */
    protected $to_generate;
    
    /**
     *  Maxium number of lines to write per file
     * 
     * @var integer
     */
    protected $write_count;
        
    
    /**
     * The entity name
     * 
     * @var string 
     */
    protected $name;
    
    /**
     * The extension to use thus which formatter combinations
     * 
     * @var string 
     */
    protected $file_extension;
    
    
    //------------------------------------------------------------
    
     public function get_cache_limit() {
         return $this->cache_limit;
     }

     public function set_cache_limit($limit) {
         $this->cache_limit = $limit;
     }

     //----------------------------------------------------------
     
     public function get_filename_format() {
         return $this->filename_format;
     }

     public function set_filename_format($format) {
         $this->filename_format = $format;
     }

     //-----------------------------------------------------------
     
     public function get_ouput_directory() {
         return $this->output_dir;
     }

     public function set_output_directory($directory) {
         $this->output_dir = $directory;
     }

     //---------------------------------------------------------
     
     public function get_to_generate() {
         return $this->to_generate;
     }

     public function set_to_generate($rows) {
         $this->to_generate = $rows;
     }

     //----------------------------------------------------------
     
     public function get_write_count() {
         return $this->write_count;
     }

     public function set_write_count($limit) {
         $this->write_count = $limit;
     }

    //-----------------------------------------------------------
    
     
    public function get_name() {
        return $this->name;
    }

    public function set_name($name) {
        $this->name = $name;
    }

    //----------------------------------------------------------
     
    
    public function set_file_extension($ext) {
        $this->file_extension = $ext;
    }
    
    
    public function get_file_extension() {
        return $this->file_extension;
    }
    
    //-----------------------------------------------------------
}