<?php

use Carbon\Carbon;
use Insowe\DataLogger\Models\DataLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDataLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_logs_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('data_type')->comment('資料類型');
            $table->unsignedBigInteger('data_id')->comment('資料編號');
            $table->unsignedBigInteger('administrator_id')->comment('更新人員編號');
            $table->string('hash', 64)->comment('資料Hash值，用來判斷是否為新資料');
            $table->timestamp('created_at')->nullable()->comment('更新時間');
            $table->index(['data_type', 'data_id']);
            $table->comment = '資料紀錄';
        });
        
        $this->backupAndRestoreData();
        Schema::dropIfExists('data_logs');
        Schema::rename('data_logs_temp', 'data_logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dataTypes = DataLog::groupBy('data_type')->select('data_type')->get()->pluck('data_type')->toArray();
        if (count($dataTypes) == 0)
        {
            $dataTypes = ['sample'];
        }
        
        Schema::create('data_logs_temp', function (Blueprint $table) use ($dataTypes) {
            $table->bigIncrements('id');
            $table->enum('data_type', $dataTypes)->comment('資料類型');
            $table->unsignedBigInteger('data_id')->comment('資料編號');
            $table->unsignedBigInteger('administrator_id')->comment('更新人員編號');
            $table->string('hash', 64)->comment('資料Hash值，用來判斷是否為新資料');
            $table->timestamp('created_at')->nullable()->comment('更新時間');
            $table->index(['data_type', 'data_id']);
            $table->comment = '資料紀錄';
        });
        
        $this->backupAndRestoreData();
        Schema::dropIfExists('data_logs');
        Schema::rename('data_logs_temp', 'data_logs');
    }
    
    protected function backupAndRestoreData()
    {
        $pageSize = 100;
        $pageIndex = 0;
        do
        {
            $rows = DataLog::skip($pageIndex++ * $pageSize)->take($pageSize)->get()->toArray();
            foreach ($rows as $i => $row)
            {
                $rows[$i]['created_at'] = Carbon::parse($row['created_at']);
            }
            DB::table('data_logs_temp')->insert($rows);
        }
        while (count($rows) > 0);
    }
}
