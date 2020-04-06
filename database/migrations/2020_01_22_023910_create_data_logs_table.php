<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('data_type', [
                // Put data types of the app here!
            ])->comment('資料類型');
            $table->unsignedBigInteger('data_id')->comment('資料編號');
            $table->unsignedBigInteger('administrator_id')->comment('更新人員編號');
            $table->string('hash', 64)->comment('資料Hash值，用來判斷是否為新資料');
            $table->timestamp('created_at')->nullable()->comment('更新時間');
            $table->index(['data_type', 'data_id']);
            $table->comment = '資料紀錄';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_logs');
    }
}
