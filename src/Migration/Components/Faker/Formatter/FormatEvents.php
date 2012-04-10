<?php
namespace Migration\Components\Faker\Formatter;


final class FormatEvents
{
    /**
     * The formatter.schema.start event is thrown when scheam composite type starts
     * generating values
     *
     * Used in xml generation for root schema's opening tag
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onSchemaStart = 'formatter.schema.start';
    
    
    /**
     * The formatter.schema.end event is thrown when scheam composite type finishes
     * generating values.
     *
     * Used in xml generation for root schema's ending tag
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onSchemaEnd = 'formatter.schema.end';
    
    
    /**
     * The formatter.table.start event is thrown when table composite type starts
     * generating values.
     *
     * Used in xml generation for tables opening tag
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onTableStart = 'formatter.table.start';
    
    
    /**
     * The formatter.table.end event is thrown when table composite type finishes
     * generating values.
     *
     * Used in xml generation for table closing tag
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onTableEnd = 'formatter.table.end';
    
    /**
     * The formatter.row.start event is thrown when table composite type start
     * generating row values.
     *
     * Used in xml generation for rows opening tag.
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onRowStart = 'formatter.row.start';
   
    /**
     * The formatter.row.end event is thrown when table composite type finishes
     * generating row values.
     *
     * Used in xml generation for rows closing tag. also be used in sql to
     * generate insert row.
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onRowEnd = 'formatter.row.end';
    
    /**
     * The formatter.column.start event is thrown when column composite type starts
     * generating a row value.
     *
     * Used in xml generation for value opening tag. 
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onColumnStart = 'formatter.column.start';
    
    /**
     * The formatter.column.generate event is thrown when column composite type has
     * generated a row value.
     *
     * Used in xml generation for character data in a value tag or wait for next event
     * for closing tag and value
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onColumnGenerate = 'formatter.column.generate';
    
    
    /**
     * The formatter.column.end event is thrown when column composite type finishes
     * generating a row value.
     *
     * Used in xml generation for value closing tag. value be included with in the event 
     *
     * The event listener receives an Migration\Components\Faker\Formatter\GenerateEvent
     * instance.
     *
     * @var string
     */
    const onColumnEnd = 'formatter.column.end';
    
}


/* End of File */