<?php

Namespace Data\Config;

/**
 * Description of Option_Names
 *
 * @author lewis
 */
class Option_Names  extends Abstract_Option {
   
    /**
     *  A string that contains the format of the name
     * 
     *  Name = Male, Femail Name
     *  MaleName = male first name
     *  FemaleName = female first name
     *  Surname = a surname.
     *  Inital = random inital
     * 
     *  Example
     * 
     *  MaleName Surname = 'Mike Smith'
     *  Surname FemaleName = 'Debra Smith'
     *  MaleName Inital, Surname = 'Mike M, Smith'
     *  
     */
    public $definition;
    
    
    
    
}
/* End of file */