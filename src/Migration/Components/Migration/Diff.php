<?php

namespace Migration\Components\Migration;

use \SplHeap;

/*
 * class Diff
 */

class Diff  {

    //  -------------------------------------------------------------------------
    # Properties

    /**
      *  @var SplHeap
      */
    protected $file_heap;

    /**
      *  @var SplHeap
      */
    protected $database_heap;

    /*
     * __construct()
     *
     * @param SplHeap $file
     * @param SplHeap $database
     * @access public
     */

    public function __construct(SplHeap $file, SplHeap $database)
    {
        $this->file_heap = $file;
        $this->database_heap = $database;
    }

    //  -------------------------------------------------------------------------
    # Diff

    /**
      *  Find nodes in file heap
      *  that are absant from the database heap
      *
      *  @return Collection
      */
    public function diff()
    {
        $difference = new Collection();

        # iterate over the file

    }


}

/* End of File */
