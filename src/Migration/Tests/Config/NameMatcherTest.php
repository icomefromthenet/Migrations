<?php
namespace Migration\Test\Config;

use Migration\Components\Config\NameMatcher;
use Migration\Tests\Base\AbstractProject;

class NameMatcherTest extends AbstractProject
{
    
    public function testNameMatcherSplit()
    {
        $name = array('UAT','custa','CONNNAME');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $nameSegements = $matcher->getNameParts();
        
        $this->assertEquals('UAT',$nameSegements[0]);
        $this->assertEquals('CUSTA',$nameSegements[1]);
        $this->assertEquals('CONNNAME',$nameSegements[2]);
        
    }
    
    
    
    public function testNameMatcherSucessulMatchesWildCard()
    {
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array('UA*','custa','*NN*');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertTrue($matcher->isMatch(implode('.',$nameWild)));
        
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array('UAT.*');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertTrue($matcher->isMatch(implode('.',$nameWild)));
        
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array();
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertTrue($matcher->isMatch(implode('.',$nameWild)));
        
        
    }
   
    
    public function testNameMatcherFailedMatches()
    {
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array('UUA*','custa','*NN*');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertFalse($matcher->isMatch(implode('.',$nameWild)));
        
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array('*','custa','*NNT');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertFalse($matcher->isMatch(implode('.',$nameWild)));
        
        
        
    }
    
    public function testNameMatcherSucessulMatchesNoWildCard()
    {
        $name = array('UAT','custa','CONNNAME');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertTrue($matcher->isMatch(implode('.',$name)));
        
        $name = array('UAT','custa','CONNNAME');
        $nameWild = array('UAT');
        
        $matcher = new NameMatcher(implode('.',$name));
        
        $this->assertTrue($matcher->isMatch(implode('.',$nameWild)));
        
       
        
    }
    
    
    
}
/* End of File */