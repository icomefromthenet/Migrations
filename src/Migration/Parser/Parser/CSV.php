<?php
namespace Migration\Parser\Parser;

use Migration\Parser\ParserInterface;
use Migration\Parser\FileInterface;
use Migration\Parser\ParseOptions;
use Migration\Parser\Event\HeaderParsed;
use Migration\Parser\Event\RowParsed;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CSV implements ParserInterface
{

    protected $field_separator;
    protected $text_delimiter;
    protected $eol_ignorecr;
    protected $eof;
    
    //--------------------------------------------------------------------------

    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event_class;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->event_class = $dispatcher;
    }

    //--------------------------------------------------------------------------

    public function read(FileInterface $file)
    {

        $done = false;
        $field = "";
        $record = array();
        $position = 0;
        $inquote = false;

        while (!$done) {
            $char = $file->fgetc();

            //check for file loop
            // see reasons http://www.php.net/manual/en/function.feof.php#70715;
            if ($file->feof()) {
                return FALSE;
            }

            $usechar = false;
            $commit = false;

            if ($char === false) {
                $done = true;
                $commit = true;
            } else
                switch ($char) {
                    case "\r":
                        if ($this->eol_ignorecr) {
                            break;
                        }
                    case "\n":
                        if (($position > 1) && !$inquote) {
                            $commit = true;
                            $done = true;
                        } break;
                    case $this->field_separator:
                        if (!$inquote) {
                            $commit = true;
                        } else {
                            $usechar = true;
                        } break;
                    case $this->text_delimiter:
                        if ($this->text_delimiter != chr(0)) {
                            $inquote = !$inquote;
                        } break;
                    default: $usechar = true;
                        break;
                }

            if ($usechar) {
                $position++;
                $field .= $char;
            }

            if ($commit && $position) {
                $record[] = $field;
                $field = "";
            }

            if ($file->feof()) {
                $done = true;
            }
        }


        return $record;
    }

    //--------------------------------------------------------------------------

    public function parse(FileInterface $file, ParseOptions $options)
    {

        $done = false;
        $this->field_separator = $options->getFieldSeperator();
        $this->text_delimiter = $options->getDeliminator();
        $this->eol_ignorecr = $options->getEolIgnoreChr();
        $header = NULL;

        $skip_rows = $options->getSkipRows();

        //skip the number of linex
        while ($skip_rows--) {
            $this->read($file);
        }

        //fetch the header row
        if ($options->getHasHeaderRow() === TRUE) {
            $header = $this->read($file);

            // send the record to the event
            $this->event_class->dispatch('header_parsed',new HeaderParsed($header,0));
            
        }

        $row = 0;

        while (!$file->feof()) {

            if (($record = $this->read($file)) !== FALSE) {

                $user_record = array();
                $record_pointer = 0;

                if ($header !== NULL) {

                    foreach ($header as $v) {
                        $user_record[$v] = $record[$record_pointer];
                        $record_pointer++;
                    }
                } else {

                    foreach ($record as $v) {
                        $user_record["FIELD" . ($record_pointer + 1)] = $record[$record_pointer];
                        $record_pointer++;
                    }
                }
                ++$row;

                // send the record to the event
                $this->event_class->dispatch('row_parsed',new RowParsed($user_record,$row));
            }
        }

        $file->fclose();

        return true;
    }

    //---------------------------------------------------------------------------
}

/* End of File */