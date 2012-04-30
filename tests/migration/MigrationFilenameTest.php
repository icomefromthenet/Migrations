<?php

use Migration\Components\Migration;

require_once __DIR__ .'/../base/AbstractProject.php';

class MigrationFilenameTest extends AbstractProject
{


    public function testGenerate()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate();

        $this->assertStringMatchesFormat('%d_%d_%d_%d_%d_%d_Migration',$stamp);

    }

    public function testParseGoodFormat()
    {
        $file = new \Migration\Components\Migration\FileName();
        $stamp = $file->generate();
        $file_name = $file->parse($stamp);

        $this->assertStringMatchesFormat('%d',$file_name);
    }



}
/* End of File */
