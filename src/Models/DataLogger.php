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
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this))) {
            $item = self::withTrashed()->find($this->id);
        }
        else {
            $item = self::find($this->id);
        }
        
        if (is_null($item))
        {
            return $this->toJson();
        }
        else
        {
            $item->setHidden([
                'updated_at',
                'deleted_at',
                'log_id',
            ]);

            return $item->toJson();
        }
    }
    
    public function setLogId($logId)
    {
        $this->log_id = $logId;
        $this->save();
    }
}