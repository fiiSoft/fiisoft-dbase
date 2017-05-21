<?php

namespace FiiSoft\Tools\Dbase;

use RuntimeException;

final class DbaseReader
{
    /**
     * @var DbfFile[]
     */
    private $openedFiles = [];
    
    /**
     * @throws RuntimeException if extension dbase is not loaded
     */
    public function __construct()
    {
        if (!extension_loaded('dbase')) {
            throw new RuntimeException('Extension dbase is required to operate with DBF files');
        }
    }
    
    /**
     * Open DBF file to read-only.
     *
     * @param string $dbFile path to DBF file
     * @throws RuntimeException if DBF file cannot be open to read
     * @return DbfFile
     */
    public function open($dbFile)
    {
        if (!isset($this->openedFiles[$dbFile])) {
            $this->openedFiles[$dbFile] = new DbfFile($dbFile);
        }
        
        $dbf = $this->openedFiles[$dbFile];
        $dbf->reopen();
        
        return $dbf;
    }
}