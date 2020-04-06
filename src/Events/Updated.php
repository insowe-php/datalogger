<?php

namespace Insowe\DataLogger\Events;

use Illuminate\Queue\SerializesModels;
use Insowe\DataLogger\Models\IData;

class Updated
{
    use SerializesModels;
    
    /**
     * 異動後的資料
     * @var Insowe\DataLogger\Models\IData 
     */
    public $data;
    
    public $administratorId;
    
    public function __construct(IData $data, $administratorId)
    {
        $this->data = $data;
        $this->administratorId = $administratorId;
    }
}
