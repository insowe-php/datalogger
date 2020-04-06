<?php

namespace Insowe\DataLogger\Models;

use Str;

trait DataLogger
{
    public function getDataLogId()
    {
        return $this->id;
    }
    
    public static function getDataLogType()
    {
        $className = substr(strrchr(__CLASS__, "\\"), 1); 
        return Str::kebab($className);
    }
    
    public function toLogFile()
    {
        $item = self::find($this->id);
        $item->setHidden([
            'updated_at',
            'deleted_at',
            'log_id',
        ]);
        
        return $item->toJson();
    }
    
    public function setLogId($logId)
    {
        $this->log_id = $logId;
        $this->save();
    }
}