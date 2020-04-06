<?php

namespace Insowe\DataLogger\Models;

interface IData
{
    public function getDataLogId();
    
    public static function getDataLogType();
    
    public function toLogFile();
    
    public function setLogId($logId);
}
