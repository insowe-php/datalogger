<?php

namespace Insowe\DataLogger\Listeners;

use Insowe\DataLogger\Events\Updated;
use Insowe\DataLogger\Jobs\PutContentToCloud;
use Insowe\DataLogger\Models\DataLog;

class DataLoggerListener
{
    /**
     * Handle the event.
     *
     * @param  Insowe\DataLogger\Events\Updated  $event
     * @return void
     */
    public function handle(Updated $event)
    {
        $json = $event->data->toLogFile();
        $logData = [
            'data_type' => $event->data->getDataLogType(),
            'data_id' => $event->data->getDataLogId(),
            'hash' => hash('sha256', $json, false),
        ];
        
        $log = DataLog::where($logData)->first();
        if (is_null($log))
        {
            $logData['administrator_id'] = $event->administratorId;
            $log = DataLog::create($logData);
            
            // Upload Content to Cloud
            $path = "data-log/{$logData['data_type']}/{$logData['data_id']}/{$logData['hash']}.log";
            dispatch(new PutContentToCloud($path, $json));
        }
        $event->data->setLogId($log->id);
    }
}
