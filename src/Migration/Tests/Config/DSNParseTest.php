<?php

namespace Migration\Tests;

use Migration\Tests\Base\AbstractProject,
    Migration\Components\Config\DSNParser;


class DSNParserTest  extends AbstractProject
{
    
    public function testParseODBC()
    {
        $parser = new DSNParser();
        $dsn    = 'odbc(access)://admin@/datasourcename';
        
        $config = $parser->parse($dsn);
        
        $this->assertEquals('odbc',$config['phptype']);
        $this->assertEquals('access',$config['dbsyntax']);
        $this->assertEquals('admin',$config['username']);
        $this->assertFalse($config['password']);
        $this->assertEquals('datasourcename',$config['database']);
        $this->assertEquals('tcp',$config['protocol']);
        
    }
    
    public function testMysqlDSN()
    {
        $parser = new DSNParser();
        $dsn    = 'mysql://user@unix(/path/to/socket)/pear';
        $config = $parser->parse($dsn);
        
        $this->assertEquals('mysql',$config['phptype']);
        $this->assertEquals('mysql',$config['dbsyntax']);
        $this->assertEquals('user',$config['username']);
        $this->assertFalse($config['password']);
        $this->assertEquals('pear',$config['database']);
        $this->assertEquals('unix',$config['protocol']);
        $this->assertEquals('/path/to/socket',$config['socket']);
        
    }
    
    public function testMysqlSqlite()
    {
        $parser = new DSNParser();
        $dsn    = 'sqlite:////full/unix/path/to/file.db?mode=0666';
        $config = $parser->parse($dsn);
        
        $this->assertEquals('sqlite',$config['phptype']);
        $this->assertEquals('sqlite',$config['dbsyntax']);
        $this->assertFalse($config['username']);
        $this->assertFalse($config['password']);
        $this->assertEquals('/full/unix/path/to/file.db',$config['database']);
        $this->assertEquals('tcp',$config['protocol']);
        $this->assertFalse($config['socket']);
        
        #extras
        $this->assertEquals('0666',$config['mode']);
    }
    
    public function testMysqlPgsql()
    {
        $parser = new DSNParser();
        $dsn    = 'pgsql://user:pass@tcp(localhost:5555)/pear';
        $config = $parser->parse($dsn);
        
        $this->assertEquals('pgsql',$config['phptype']);
        $this->assertEquals('pgsql',$config['dbsyntax']);
        $this->assertEquals('user',$config['username']);
        $this->assertEquals('pass',$config['password']);
        $this->assertEquals('pear',$config['database']);
        $this->assertEquals('tcp',$config['protocol']);
        $this->assertFalse($config['socket']);
        $this->assertEquals('5555',$config['port']);
        $this->assertEquals('localhost',$config['hostspec']);
    }
    
    
}
/* End of File */