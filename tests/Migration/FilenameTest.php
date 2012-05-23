<?php
namespace Migration\Tests\Migration;

use Migration\Components\Migration,
    Migration\Tests\Base\AbstractProject;

class FilenameTest extends AbstractProject
{


    public function testGenerateDefaultSuffix()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate();

        $this->assertStringMatchesFormat('migration_%d_%d_%d_%d_%d_%d',$stamp);

        
    }

    public function testGenerateCustomSuffix()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate('Added Float Column To Table x');

        $this->assertStringMatchesFormat('added_float_column_to_table_x_%d_%d_%d_%d_%d_%d',$stamp);
    }
    
    /**
      *  @expectedException \Migration\Components\Migration\Exception 
      */
    public function testGenerateCustomSuffixNumericStart()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate('9 Added Float Column To Table x');

        $this->assertStringMatchesFormat('%s_%d_%d_%d_%d_%d_%d',$stamp);
    }
    
    public function testGenerateRemovesFileExtension()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate('Added Float Column To Table x.php');

        $this->assertStringMatchesFormat('%s_%d_%d_%d_%d_%d_%d',$stamp);
        $this->assertFalse(strpos($stamp,'.php'));
    }

    
    public function testParseDefaultFormat()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate();
        $file_name = $file->parse($stamp);

        $this->assertStringMatchesFormat('%d',$file_name);
    }
    
    
    public function testParseCustomFormat()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate('Added Float Column To Table x');
        
        $file_name = $file->parse($stamp);
        
        $this->assertStringMatchesFormat('%d',$file_name);
    }

    /**
      *  @expectedException Migration\Components\Migration\Exception
      *  @expectedExceptionMessage File Name is invalid at::a bad stamp
      */
    public function testParseBadCustomFormat()
    {
        $file = new \Migration\Components\Migration\FileName();
        $file_name = $file->parse('a bad stamp');
        
    }


}
/* End of File */
