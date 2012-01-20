<?php
require_once __DIR__ .'../Base/BaseDb.php'

class HandlerQuotePgsqlTest extends BaseDb
{

    public function setup()
    {
        if($this->getPgsql() === null) {
                 $this->markTestSkipped();
            return;
        }

    }

    //  -------------------------------------------------------------------------
    # Tests Start

    public function testIdentifierQuotingNoEscape()
    {
        $db = $this->getPgsql();
        $quoteChars = array( '"', '"' );

        $this->assertEquals(
            $quoteChars[0] . 'TestIdentifier' . $quoteChars[1],
            $db->quoteIdentifier( 'TestIdentifier' )
        );

    }

    //  -------------------------------------------------------------------------


    public function testIdentifierQuotingEscape()
    {
        $db = $this->getPgsql();
        $quoteChars = array( '"', '"' );

         $this->assertEquals(
            $quoteChars[0] . "Test" . $quoteChars[1] . $quoteChars[1] . "Identifier" . $quoteChars[1],
            $db->quoteIdentifier( "Test" . $quoteChars[1] . "Identifier" )
        );

    }


    //  -------------------------------------------------------------------------

}
/* End of File */
