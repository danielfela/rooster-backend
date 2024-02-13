<?php

namespace Library;

use Library\CSV\Exception;

class Adapter
{
    public $fileResource;
    public bool $hasHeader = false;

    public function __construct($_file, $_mode = null, $_hasHeader = false)
    {
        $this->fileResource = fopen($_file, $_mode ?? 'a+');
        $this->hasHeader = $_hasHeader;
        if ($this->fileResource === false) {
            throw new Exception('Unable to open file: ' . $_file);
        }
    }

    public function writeLine($_lineData)
    {
        if ($this->fileResource === false) {
            return;
        }

        fputcsv($this->fileResource, $_lineData);
    }

    public function write($_data)
    {
        if ($this->fileResource === false) {
            return;
        }

        foreach ($_data as $line) {
            fputcsv($this->fileResource, $line);
        }

        $this->close();
    }

    public function get()
    {//unfinished
        if ($this->fileResource === false) {
            return;
        }
    }

    public function close()
    {
        if ($this->fileResource === false) {
            return;
        }

        fclose($this->fileResource);
        $this->fileResource = false;
    }

}
