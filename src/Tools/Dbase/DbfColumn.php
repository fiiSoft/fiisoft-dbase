<?php

namespace FiiSoft\Tools\Dbase;

use InvalidArgumentException;

final class DbfColumn
{
    private static $REQUIRED_KEYS = ['name', 'type', 'length', 'precision', 'format', 'offset'];
    
    /**
     * @var string The name of the column
     */
    public $name;
    
    /**
     * @var string The human-readable name for the dbase type of the column (i.e. date, boolean, etc.)
     *              The supported field types are listed in the http://php.net/manual/en/intro.dbase.php
     */
    public $type;
    
    /**
     * @var integer The number of bytes this column can hold
     */
    public $length;
    
    /**
     * @var integer The number of digits of decimal precision for the column
     */
    public $precision;
    
    /**
     * @var string A suggested printf() format specifier for the column
     */
    public $format;
    
    /**
     * @var integer The byte offset of the column from the start of the row
     */
    public $offset;
    
    /**
     * @param array $columnInfo
     * @throws InvalidArgumentException
     */
    public function __construct(array $columnInfo)
    {
        foreach (self::$REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $columnInfo)) {
                throw new InvalidArgumentException('There is no key '.$key.' in array '.print_r($columnInfo, true));
            }
        }
        
        $this->name = $columnInfo['name'];
        $this->type = $columnInfo['type'];
        $this->length = $columnInfo['length'];
        $this->precision = $columnInfo['precision'];
        $this->format = $columnInfo['format'];
        $this->offset = $columnInfo['offset'];
    }
    
    public function toString()
    {
        return 'name: '.$this->name.', type: '.$this->type.', length: '.$this->length
            .', precision: '.$this->precision.', format: '.$this->format.', offset: '.$this->offset;
    }
    
    public function __toString()
    {
        return $this->toString();
    }
}