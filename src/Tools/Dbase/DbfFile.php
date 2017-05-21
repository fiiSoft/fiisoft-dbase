<?php

namespace FiiSoft\Tools\Dbase;

use InvalidArgumentException;
use IteratorAggregate;
use OutOfBoundsException;
use RuntimeException;

final class DbfFile implements IteratorAggregate
{
    /** @var string path to dbf file */
    private $dbFile;
    
    /** @var resource handler to opened db file */
    private $db;
    
    /** @var DbfHeader */
    private $header;
    
    /** @var integer */
    private $numOfRows;
    
    /**
     * @param string $dbFile path to DBF file to open
     * @throws RuntimeException
     */
    public function __construct($dbFile)
    {
        $this->dbFile = $dbFile;
        $this->open();
    }
    
    public function __destruct()
    {
        $this->close();
    }
    
    /**
     * Get information about columns
     *
     * @throws RuntimeException
     * @return DbfHeader
     */
    public function header()
    {
        if (!$this->db) {
            $this->open();
        }
        
        if ($this->header === null) {
            $header = dbase_get_header_info($this->db);
            if ($header === false) {
                throw new RuntimeException('Cannot read header from '.$this->dbFile);
            }
            
            $this->header = new DbfHeader($header);
        }
        
        return $this->header;
    }
    
    /**
     * Close current opened DBF file.
     * This will be done automatically when object is destroyed.
     *
     * @return void
     */
    public function close()
    {
        if ($this->db) {
            dbase_close($this->db);
            $this->db = null;
        }
    }
    
    /**
     * Reopen this DBF file for read. If it is already open, do nothing.
     *
     * @throws RuntimeException
     * @return void
     */
    public function reopen()
    {
        if (!$this->db) {
            $this->open();
        }
    }
    
    /**
     * Tell if this DBF file is open to read.
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->db === null;
    }
    
    /**
     * @throws RuntimeException if cannot open DBF file to read
     */
    private function open()
    {
        $db = dbase_open($this->dbFile, 0);
        if ($db === false) {
            throw new RuntimeException('Cannot open DBF file '.$this->dbFile);
        }
    
        $this->db = $db;
    
        $this->numOfRows = dbase_numrecords($this->db);
        if ($this->numOfRows === false) {
            throw new RuntimeException('Unable to get number of rows in '.$this->dbFile);
        }
    }
    
    /**
     * Get path to opened DBF file.
     *
     * @return string
     */
    public function path()
    {
        return $this->dbFile;
    }
    
    /**
     * Get name of DBF file (with extension, without path).
     *
     * @return string
     */
    public function name()
    {
        return basename($this->dbFile);
    }
    
    /**
     * Get number of rows (records) in this DBF.
     *
     * @throws RuntimeException
     * @return int
     */
    public function numOfRows()
    {
        if (!$this->db) {
            $this->open();
        }
        
        return $this->numOfRows;
    }
    
    /**
     * Get values of columns in single row as array.
     * Notice that in dbf files numbers of rows start from 1.
     *
     * @param integer $num index of row, starting from 1
     * @param bool $assoc if true then return associative array, if false then then return indexed array
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * @throws RuntimeException
     * @return array|false array on success, false if failed
     */
    public function row($num, $assoc = true)
    {
        if (!$this->db) {
            $this->open();
        }
        
        if (!is_int($num)) {
            throw new InvalidArgumentException('Param num must be an integer');
        } elseif ($num < 1 || $num > $this->numOfRows) {
            throw new OutOfBoundsException('Valid number of row is between 1 and '.$this->numOfRows);
        }
    
        if ($assoc) {
            return dbase_get_record_with_names($this->db, $num);
        }
        
        return dbase_get_record($this->db, $num);
    }
    
    /**
     * @throws RuntimeException
     * @return DbfIterator
     */
    public function getIterator()
    {
        if (!$this->db) {
            $this->open();
        }
        
        return new DbfIterator($this->db, $this->numOfRows);
    }
}