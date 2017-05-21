<?php

namespace FiiSoft\Tools\Dbase;

use Iterator;

final class DbfIterator implements Iterator
{
    /** @var resource handler to opened db file */
    private $db;
    
    /** @var integer */
    private $limit;
    
    /** @var integer */
    private $num = 1;
    
    /** @var bool */
    private $assoc = true;
    
    /**
     * @param resource $db a pointer to opened dbf file
     * @param integer $limit
     */
    public function __construct($db, $limit = null)
    {
        $this->db = $db;
        $this->limit = $limit ?: dbase_numrecords($this->db);
    }
    
    public function current()
    {
        if ($this->assoc) {
            return dbase_get_record_with_names($this->db, $this->num);
        }
        
        return dbase_get_record($this->db, $this->num);
    }
    
    public function next()
    {
        ++$this->num;
    }
    
    public function key()
    {
        return $this->num;
    }
    
    public function valid()
    {
        return $this->num <= $this->limit;
    }
    
    public function rewind()
    {
        $this->num = 1;
    }
    
    public function enableAssocRowsMode()
    {
        $this->assoc = true;
    }
    
    public function enableNumRowsMode()
    {
        $this->assoc = false;
    }
}