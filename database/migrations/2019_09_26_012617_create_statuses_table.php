<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->text('content');
            //文本存储微博内容
            $table->integer('user_id')->index();
            //存发布者id，并增加index索引
            $table->index(['created_at']);
            //为自动创建的索引create_at增加近index索引
            $table->timestamps();
            //自动生成创建时间段created_at和更新时间段updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
