<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 50);
            $table->integer('ibs_id');
            $table->integer('invoice_count');
            $table->timestamp('active_date')->nullable();
            $table->string('comment', 500)->nullable();
            $table->boolean('blocked')->default(false);
            $table->boolean('recreatePwd')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('ibs_id');
            $table->dropColumn('invoice_count');
            $table->dropColumn('active_date');
            $table->dropColumn('comment');
            $table->dropColumn('blocked');
            $table->dropColumn('recreatePwd');
        });
    }
}
