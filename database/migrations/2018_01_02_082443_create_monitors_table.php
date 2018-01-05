<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 监控任务
 * Class CreateMonitorsTable
 */
class CreateMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->text('request_url');
            $table->text('request_method');
            $table->text('request_headers'); // 不包含首行
            $table->text('request_body');

            $table->unsignedInteger('request_interval_second');

            $table->string('match_type'); // include / http_status_code / timeout
            $table->boolean('match_reverse')->default(false); // 相反匹配，例如timeout 翻转的话，就是如果响应时间快于x毫秒，则通知
            $table->string('match_content'); // 内容或状态吗
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('monitors');
    }
}