<?php
namespace Migration;

use Symfony\Component\ClassLoader\UniversalClassLoader;


class Autoload extends UniversalClassLoader
{
    
    //  -------------------------------------------------------------------------
    # Extension namespace
    
    /**
      *  @var string the namespace to use for extensions 
      */
    protected $extension_namespace = array();
    
       
    /**
      *  Sets the extension namespace
      *
      *  @param string $space
      */
    public function setExtensionNamespace($space,$folder)
    {
        $this->extension_namespace[$space] = $folder;
    }
    
    /**
      *  Fetches the set extension namespace
      *
      *  @retrn string
      */
    public function getExtensionNamespace()
    {
        return $this->extension_namespace;
    }
    
    //  -------------------------------------------------------------------------
    # Namespace Extension Filter 
    
    protected $filter;
    
    public function setFilter(\Closure $filter) {
        $this->filter = $filter;
    }
    
    //  -------------------------------------------------------------------------
    # findFile Override
    
    
    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     *
     * @return string|null The path, if found
     */
    public function findFile($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }

        
        # Test if we have a namespace class name 
       if (false === $pos = strrpos($class, '\\')) {
             # Use normal loading
            return parent::findFile($class);
       }
       
        # fetch the extension namespaces
        
        #find if any match the current namespace
        foreach($this->getExtensionNamespace() as $ext_namespace => $ext_folder) {
            
            $pos = strrpos($class, '\\');
            $namespace = substr($class, 0, $pos);
            $className = substr($class, $pos + 1);
            
            # check if the extension namespace found in the current class (at string 0)
            if  (0 === strpos($namespace,$ext_namespace)) {
             
                # apply call back to filter the namespace migration_components'
                if($this->filter instanceof \Closure) {
                    $filter = $this->filter;
                    $namespace = $filter($namespace);
                }
             
                $normalizedClass = strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $className).'.php');
            
                $file = $ext_folder.DIRECTORY_SEPARATOR.$normalizedClass;
                if (is_file($file)) {
                    return $file;
                }
       
            }
        }
        
        # Use normal loading
        return parent::findFile($class);

    }
    
}

/* End of File */