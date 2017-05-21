<?php

namespace FiiSoft\Tools\Dbase;

use InvalidArgumentException;

final class DbfHeader
{
    /**
     * @var DbfColumn[]
     */
    private $columns = [];
    
    /**
     * @param array $header it MUST be valid array returned from dbase_get_header_info()
     */
    public function __construct(array $header)
    {
        foreach ($header as $columnInfo) {
            $this->columns[] = new DbfColumn($columnInfo);
        }
    }
    
    /**
     * Get column header by its name or position (counting from 0).
     *
     * @param string|int $nameOrNumber name of column or its number if integer
     * @throws InvalidArgumentException
     * @return DbfColumn|false column if found, false otherwise
     */
    public function getColumn($nameOrNumber)
    {
        if (is_int($nameOrNumber)) {
            if (isset($this->columns[$nameOrNumber])) {
                return $this->columns[$nameOrNumber];
            }
        } elseif (is_string($nameOrNumber)) {
            foreach ($this->columns as $column) {
                if ($column->name === $nameOrNumber) {
                    return $column;
                }
            }
        } else {
            throw new InvalidArgumentException('Invalid param nameOrNumber');
        }
        
        return false;
    }
    
    /**
     * Get number of columns in table.
     *
     * @return int
     */
    public function numberOfColumns()
    {
        return count($this->columns);
    }
    
    /**
     * Get info about columns.
     *
     * @return DbfColumn[]
     */
    public function columns()
    {
        return $this->columns;
    }
    
    /**
     * Get names of columns.
     *
     * @return array names of columns
     */
    public function namesOfColumns()
    {
        return array_map(function (DbfColumn $column) {
            return $column->name;
        }, $this->columns);
    }
    
    public function toString()
    {
        return implode(PHP_EOL, array_map(function (DbfColumn $column) {
            return $column->toString();
        }, $this->columns));
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}