<?php

use Migrations\Database\Options\Mssql as MssqlOptions;

require_once __DIR__ .'../Base/BaseDb.php'

class HandlerQuoteMysqlTest extends BaseDb
{

    public function setup()
    {
        if($this->getMssql() === null) {
                 $this->markTestSkipped();
            return;
        }

    }

    //  -------------------------------------------------------------------------
    # Tests Start


    public function testIdentifierQuotingNoEscape()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_COMPLIANT ) ));

        $this->assertEquals(
            $quoteChars[0] . 'TestIdentifier' . $quoteChars[1],
            $db->quoteIdentifier( 'TestIdentifier' )
        );

    }

    //  -------------------------------------------------------------------------


    public function testIdentifierQuotingEscape()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_COMPLIANT ) ));

         $this->assertEquals(
            $quoteChars[0] . "Test" . $quoteChars[1] . $quoteChars[1] . "Identifier" . $quoteChars[1],
            $db->quoteIdentifier( "Test" . $quoteChars[1] . "Identifier" )
        );

    }

    //  -------------------------------------------------------------------------

    //  -------------------------------------------------------------------------

    public function testMssqlIdentifierQuotingUntouched()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_UNTOUCHED ) ));
        $quoteChars = array( '"', '"' );
        $this->assertEquals( $quoteChars[0].'ezctesttable'.$quoteChars[1], $db->quoteIdentifier( 'ezctesttable' ));
    }

    //  -------------------------------------------------------------------------


    public function testMssqlIdentifierQuotingCompliant()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_COMPLIANT ) ));

        $this->assertEquals( '"ezctesttable"', $db->quoteIdentifier( 'ezctesttable' ));
    }

    //  -------------------------------------------------------------------------


    public function testMssqlIdentifierQuotingLegacy()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_LEGACY ) ));

        $this->assertEquals( '[ezctesttable]', $db->quoteIdentifier( 'ezctesttable' ));
    }

    //  -------------------------------------------------------------------------

    public function testMssqlIdentifierQuotingImpl()
    {
        $db = $this->getMssql();
        $db->setOptions( new MssqlOptions( array('quoteIdentifier' => MssqlOptions::QUOTES_COMPLIANT ) ));

        try {
            $db->query('CREATE TABLE '.$db->quoteIdentifier('group') . ' ( id INT )');
            $db->query('DROP TABLE '.$db->quoteIdentifier('group') );
        }
        catch ( Exception $ex )
        {
            $this->fail( "Incorrect identifiers quoting ".$ex->getMessage() );
        }
    }


}
/* End of File */
