<?php
namespace Migration\Components\Migration;

use DateTime,
    Migration\Components\Migration\Exception\RebuildOrDownException,
    Migration\Components\Migration\Exception\RebuildRequiredException;

/*
 * Class Diff this class will run test to find if the Database can by synced
 * with the repository.
 *
 * Given the folllowing scenerio.
 *
 * A = Migrations in the repo
 * B = Migrations listed in the database
 *
 * The Database is considered to be un-syncable if the following is true
 *
 * If there are any (B \ A) > 0 [relative complement] , When migrations referenced in the database are subtracted
 * from the migrations in the repo if there are migrations in the database  list remaining
 * then the sync job will not complete successfuly.
 *
 * To fix a rebuild operation must be run.
 *
 *
 * While The Database can be synced in the following scenario, extra steps
 * are required when.
 *
 * If (A \ B) > 0 [relative complement], then there are more migrations in the repositry then
 * are referenced in the database AND IF these migrations occur
 * before the LAST migration in B (Database), then the repository must be
 * migrated down to the common parent before migrating up to latest.
 *
 *
 * If the above occurs, we will throw an exception as would be done
 * with first scenario, it is up to the developer to decided their
 * action rebuild or down
 *
 */
class Diff
{

    //  -------------------------------------------------------------------------
    # Properties

    /**
      *  @var integer[]
      */
    protected $file_list;

    /**
      *  @var integer[]
      */
    protected $database_list;
    
    
    /*
     * __construct()
     *
     * @param integer[] $file
     * @param integer[] $database
     * @access public
     */

    public function __construct(array $file, array $database)
    {
        $this->file_list = $file;
        $this->database_list = $database;
        
    }
    //  -------------------------------------------------------------------------
    # Diff

    /**
      *  Determine if the database is in unsyncable state
      *
      *
      *  A = Migrations in the repo
      *  B = Migrations listed in the database    
      *  @return boolean true if syncable
      *  @access public
      *  @throws RebuildRequiredException 
      */
    public function diffBA()
    {
        # check for (B-A) > 0 [relative complement B \ A]
        $diff = array_diff($this->database_list,$this->file_list);
        
        //var_dump($this->database_list);
        //var_dump($this->file_list);
        
        if(count($diff) > 0) {
            # throw exception out of sync and can only rebuild.
            throw new RebuildRequiredException('There migrations recorded in the Database but the files are not found in the Project. Have you been deleting migration files? Run app:build to refresh.');
        }
        
        return true;
    }
    
    /**
      *  Determine if the database is in unsyncable state
      *
      *
      *  A = Migrations in the repo
      *  B = Migrations listed in the database    
      *  @return boolean true if syncable
      *  @access public
      *  @throws RebuildOrDownException
      */
    public function diffAB()
    {
        
         # check for (A-B) > 0 [relative complement A \ B] and before last common migration
        
        $diff = array_diff($this->file_list,$this->database_list);
        
        if(count($diff) > 0) {
            # ok to have value greater than zero mean that we
            # have migrations that need to be applied but must make sure
            # that these migrations don't occur out of order.
            
            # If the first difference is less then the head of the
            # database list then we can't migrate up and out of sync
            
            if(reset($diff) < end($this->database_list) === true) {
                # out of sync but we can migrate down.
                $msg = '';
                $parent = $this->findParent();
                if($parent === false) {
                    $msg = 'There is no common parent please run build to rsync';
                } 
                else {
                    $dte = new DateTime();
                    $dte->setTimestamp($parent);
                    $msg = 'Please migrate down to parent with Date of '. $dte->format('Y_m_d_H_i_s');
                }
                
                throw new RebuildOrDownException($msg);
            }
            
        }
        
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
    # Find Parent
    
    /**
      *  Find the parent migration before two lists
      *  diverged
      *
      *  Assume there is a parent as this function would be run
      *  if (B \ A) > 0 not true
      *
      *  @return integer a timestamp
      *  @access public
      */
    public function findParent()
    {
       $left  = $this->file_list;
       $right = $this->database_list;
       $merge = $left + $right; 
       
       $diff =  array_diff($merge, array_intersect($left, $right));
       
       if(count($diff) > 0) { 
            
            # make sure the diff is ordered, might not be 
            ksort($diff);
            
            # we looking for the migration before the first diff, ie the parent
            $index = array_search(reset($diff),$merge,true) - 1;
        
            if($index > 0) {
                return $merge[$index];
            }
            
        }
 
       # no parent found must be empty or different lists      
       return false;
    }
    
}
/* End of File */
