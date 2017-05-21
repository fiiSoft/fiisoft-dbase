<?php

if (!function_exists('dbase_open')) {
    /**
     * Opens a dBase database with the given access mode.
     *
     * @param string $dbfile The name of the database. It can be a relative or absolute path to the file where
     *                          dBase will store your data.
     * @param int $mode An integer which correspond to those for the open() system call (Typically 0 means
     *                    read-only and 2 means read and write and only these two are valid).
     * @return resource
     */
    function dbase_open($dbfile, $mode) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_close')) {
    /**
     * Closes the given database link identifier.
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    function dbase_close($db) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_numrecords')) {
    /**
     * Gets the number of records (rows) in the specified database.
     *
     * Record numbers are between 1 and dbase_numrecords($db),
     * while field numbers are between 0 and dbase_numfields($db)-1.
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @return integer The number of records in the database, or FALSE if an error occurs.
     */
    function dbase_numrecords($db) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_numfields')) {
    /**
     * Gets the number of fields (columns) in the specified database.
     *
     * Field numbers are between 0 and dbase_numfields($db)-1,
     * while record numbers are between 1 and dbase_numrecords($db).
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @return integer|false The number of fields in the database, or FALSE if an error occurs.
     */
    function dbase_numfields($db) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_get_header_info')) {
    /**
     * Gets the header info of a database.
     * Returns information on the column structure of the given database link identifier.
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @return array|false An indexed array with an entry for each column in the database.
     *      The array index starts at 0. Each array element contains an associative array of column information,
     *      as described here:<pre>
    <b>name</b> The name of the column
    <b>type</b> The human-readable name for the dbase type of the column (i.e. date, boolean, etc.)
    <b>length</b> The number of bytes this column can hold
    <b>precision</b> The number of digits of decimal precision for the column
    <b>format</b> A suggested printf() format specifier for the column
    <b>offset</b> The byte offset of the column from the start of the row
    </pre>
    If the database header information cannot be read, FALSE is returned.
     */
    function dbase_get_header_info($db) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_get_record')) {
    /**
     * Gets a record from a database as an indexed array.
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @param integer $recordNumber The index of the record, started from 1.
     * @return array|false An indexed array with the record. This array will also include an associative key
     *      named deleted which is set to 1 if the record has been marked for deletion (see dbase_delete_record()).
     *      Each field is converted to the appropriate PHP type, except dates (are left as strings) and integers
     *      that would have caused an overflow (> 32 bits) are returned as strings.
     *      On error, dbase_get_record() will return FALSE.
     */
    function dbase_get_record($db, $recordNumber) {
        die('There is no function '.__FUNCTION__);
    }
}

if (!function_exists('dbase_get_record_with_names')) {
    /**
     * Gets a record from a database as an associative array.
     *
     * @param resource $db The database link identifier, returned by dbase_open() or dbase_create().
     * @param integer $recordNumber The index of the record, started from 1.
     * @return array|false An associative array with the record. This will also include a key
     *      named deleted which is set to 1 if the record has been marked for deletion
     *      (see dbase_delete_record()). Each field is converted to the appropriate PHP type,
     *      except dates (are left as strings) and integers that would have caused an overflow
     *      (> 32 bits) are returned as strings. On error, dbase_get_record_with_names() will return FALSE.
     *
     */
    function dbase_get_record_with_names($db, $recordNumber) {
        die('There is no function '.__FUNCTION__);
    }
}