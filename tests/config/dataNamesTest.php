<?php

Class dataNamesTest extends UnitTestCase {

    function __construct() {

        parent::__construct('Name Test');
    }

    public function setUp() {
        
    }

    public function tearDown() {
        
    }

    public function test_names_are_parsed() {
        $name = DataGeneratorNameQuery::create()->find();

        foreach ($name as $value) {
            echo $value->toCSV();
            echo PHP_EOL;
        }
        exit;
    }

    public function test_loading_object() {
        $option = new dataNamesOption;
        $option->definition = 'MaleName, Suername';
        $option->to_generate = 100;
        $this->assertIsA($option, 'dataNamesOption');

        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);

        $this->assertIsA($dataType, 'dataNamesDatatype');
    }

    public function test_no_def_param() {
        $option = new dataNamesOption;
        $option->to_generate = 100;

        $id = 'column_one';

        //default definition will be used
        $dataType = new dataNamesDatatype($id, $option);
        $this->assertIsA($dataType, 'dataNamesDatatype');
    }

    public function test_generate() {
        $option = new dataNamesOption;
        $option->definition = 'MaleName, Suername';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);

        $this->assertIsA($dataType->generate(), 'string');
    }

    public function test_male_name_replaced() {
        $option = new dataNamesOption;
        $option->definition = 'MaleName';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/MaleName/', $value);
    }

    public function test_female_name_replaced() {

        $option = new dataNamesOption;
        $option->definition = 'FemaleName';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/FemaleName/', $value);
    }

    public function test_surname_replaced() {
        $option = new dataNamesOption;
        $option->definition = 'Surname';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/Surname/', $value);
    }

    public function tets_name_replaced() {
        $option = new dataNamesOption;
        $option->definition = 'Name';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/Name/', $value);
    }

    public function test_inital_repalced() {

        $option = new dataNamesOption;
        $option->definition = 'Inital';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/Inital/', $value);
    }

    public function test_formats_repalced() {
        $option = new dataNamesOption;
        $option->definition = 'Surname|MaleName';
        $option->to_generate = 100;
        $id = 'column_one';

        $dataType = new dataNamesDatatype($id, $option);
        $value = $dataType->generate();

        $this->assertPattern('/^[A-za-z0-9]+$/', $value);
        $this->assertNoPattern('/Surname/', $value);
        $this->assertNoPattern('/MaleName/', $value);
    }

}