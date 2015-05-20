<?php 
namespace Migration\Components\Config;


/**
 * Used to compare two string names that are formatted with a hierarchy 
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */ 
class NameMatcher
{
    /**
     * @var array[string]
     */ 
    protected $nameParts;
    
    /**
     * Split a hierarchy  into segments with top on left and last on right 
     * 
     * @access protected
     * @param string    $sName    The hierarchy to split into segments
     */ 
    protected function splitName($sName)
    {
        return explode('.',strtoupper($sName));
    }
    
    
    public function __construct($sName)
    {
        $this->nameParts = $this->splitName($sName);
    }
    
    
    /**
     * Return the name split into arrary of segments.
     * 
     * @access public
     * @return array[string]
     */ 
    public function getNameParts()
    {
        return $this->nameParts;
    }
    
    
    
    /**
     * Check if all segments match by checking each segment in turn
     * 
     * Convert wildcard * to regex. 
     * 
     * @access public
     * @param string    $sNameQuery     The string name in format like 'UAT.custA.connName'
     * @return boolean true if the match
     */ 
    public function isMatch($sNameQuery)
    {
        $querySegments = $this->splitName($sNameQuery);
        $nameSegements = $this->getNameParts();
        $isMatch = true;
        
        // example names
        // UAT.custA
        // UAT.*
        // *.custA
        // *.custA.envA
        // *.custA.*
        
        foreach($querySegments as $index => $segment) {
            if(isset($nameSegements[$index])) {
            
                $regex      = '/'.str_replace('*','[a-zA-Z0-9.@_]+',$segment) .'/';
                $pregResult = preg_match($regex,$nameSegements[$index]);
                
                
                
                if( 1 !== $pregResult ) {
                    $isMatch = false;
                    break;
                } 
            }
        }
        
        return $isMatch;
    }
    
    
}
/* End of file */