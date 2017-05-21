<?php

namespace FiiSoft\Test\Tools\Dbase;

use FiiSoft\Tools\Dbase\DbaseReader;

class DbaseReaderTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_open_dbase_file()
    {
        $fileName = 'test.dbf';
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $fileName;
    
        $reader = new DbaseReader();
        $dbase = $reader->open($filePath);
    
        self::assertFalse($dbase->isClosed());
        self::assertSame($fileName, $dbase->name());
        self::assertSame($filePath, $dbase->path());
        
        $dbase->close();
        self::assertTrue($dbase->isClosed());
        
        $dbase->reopen();
        self::assertFalse($dbase->isClosed());
        
        $dbase->close();
    }
    
    public function test_it_can_read_info_about_columns()
    {
        $reader = new DbaseReader();
        $dbase = $reader->open(__DIR__ . DIRECTORY_SEPARATOR . 'test.dbf');
        
        $header = $dbase->header();
        self::assertSame(2, $header->numberOfColumns());
        self::assertSame(['SL_KIER', 'KIEROWCY'], $header->namesOfColumns());
        self::assertFalse($header->getColumn('no such column - should return false'));
    
        try {
            $header->getColumn(false);
            self::fail('Exception expected');
        } catch (\InvalidArgumentException $e) {
        }
        
        $columns = $header->columns();
        self::assertCount(2, $columns);
        
        $col = $header->getColumn('KIEROWCY');
        self::assertSame($col, $columns[1]);
        self::assertSame($col, $header->getColumn(1));
        
        self::assertSame('KIEROWCY', $col->name);
        self::assertSame('%-4s', $col->format);
        self::assertSame('character', $col->type);
        self::assertSame(2, $col->offset);
        self::assertSame(0, $col->precision);
        self::assertSame(4, $col->length);
    
        $colAsString = 'name: KIEROWCY, type: character, length: 4, precision: 0, format: %-4s, offset: 2';
        self::assertSame($colAsString, $col->toString());
        self::assertSame($colAsString, (string) $col);
    
        $headerAsString = 'name: SL_KIER, type: character, length: 1, precision: 0, format: %-1s, offset: 1
name: KIEROWCY, type: character, length: 4, precision: 0, format: %-4s, offset: 2';
        
        self::assertSame($headerAsString, $header->toString());
        self::assertSame($headerAsString, (string) $header);
    }
    
    public function test_it_can_read_rows_from_dbase_file()
    {
        $reader = new DbaseReader();
        $dbase = $reader->open(__DIR__ . DIRECTORY_SEPARATOR . 'test.dbf');
        
        self::assertSame(3, $dbase->numOfRows());
        
        $rowAssoc = $dbase->row(1);
        self::assertInternalType('array', $rowAssoc);
        self::assertCount(3, $rowAssoc);
        
        self::assertSame(['SL_KIER', 'KIEROWCY', 'deleted'], array_keys($rowAssoc));
        self::assertSame(['1', '!Uwa', 0], array_values($rowAssoc));
        
        $rowAssoc = $dbase->row(2);
        self::assertSame(['SL_KIER', 'KIEROWCY', 'deleted'], array_keys($rowAssoc));
        self::assertSame(['2', 'Zkaz', 0], array_values($rowAssoc));
        
        $rowAssoc = $dbase->row(3);
        self::assertSame(['SL_KIER', 'KIEROWCY', 'deleted'], array_keys($rowAssoc));
        self::assertSame(['3', 'Ndot', 0], array_values($rowAssoc));
    }
    
    public function test_it_can_read_rows_as_numerical_arrays()
    {
        $reader = new DbaseReader();
        $dbase = $reader->open(__DIR__ . DIRECTORY_SEPARATOR . 'test.dbf');
    
        $rowAssoc = $dbase->row(1, false);
        self::assertSame([0, 1, 'deleted'], array_keys($rowAssoc));
        self::assertSame(['1', '!Uwa', 0], array_values($rowAssoc));
    }
    
    public function test_it_will_throw_exception_on_invalid_index_of_row()
    {
        $reader = new DbaseReader();
        $dbase = $reader->open(__DIR__ . DIRECTORY_SEPARATOR . 'test.dbf');
    
        try {
            $dbase->row('a');
            self::fail('Exception expected');
        } catch (\InvalidArgumentException $e) {
            self::assertSame('Param num must be an integer', $e->getMessage());
        }
    
        try {
            $dbase->row(0);
            self::fail('Exception expected');
        } catch (\OutOfBoundsException $e) {
            self::assertSame('Valid number of row is between 1 and 3', $e->getMessage());
        }
    
        try {
            $dbase->row(4);
            self::fail('Exception expected');
        } catch (\OutOfBoundsException $e) {
            self::assertSame('Valid number of row is between 1 and 3', $e->getMessage());
        }
    }
    
    public function test_it_is_iterable()
    {
        $reader = new DbaseReader();
        $dbase = $reader->open(__DIR__ . DIRECTORY_SEPARATOR . 'test.dbf');
        $iterator = $dbase->getIterator();
        
        $iterator->enableNumRowsMode();
        
        $expected = [
            [0 => '1', 1 => '!Uwa', 'deleted' => 0],
            [0 => '2', 1 => 'Zkaz', 'deleted' => 0],
            [0 => '3', 1 => 'Ndot', 'deleted' => 0],
        ];
        
        $i = 0;
        foreach ($iterator as $item) {
            self::assertSame($expected[$i++], $item);
        }
        
        $iterator->enableAssocRowsMode();
        
        $expected = [
            ['SL_KIER' => '1', 'KIEROWCY' => '!Uwa', 'deleted' => 0],
            ['SL_KIER' => '2', 'KIEROWCY' => 'Zkaz', 'deleted' => 0],
            ['SL_KIER' => '3', 'KIEROWCY' => 'Ndot', 'deleted' => 0],
        ];
        
        $i = 0;
        foreach ($iterator as $item) {
            self::assertSame($expected[$i++], $item);
        }
    }
}
