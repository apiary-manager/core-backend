<?php

namespace App\Helper\CSVHelper;

class CSVString
{
    private $_array = null;

    public function __construct($values)
    {
        $this->_array = $values;

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getArray()
    {
        return $this->_array;
    }
}