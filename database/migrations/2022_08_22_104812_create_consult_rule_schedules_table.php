<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultRuleSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consult_rule_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consult_rule_id');
            $table->foreign('consult_rule_id')
                ->references('id')
                ->on('consult_rules')
                ->onUpdate('cascade')
                ->onDelete('cascade');            
            $table->text('day');
            $table->timestamp("start_time")->nullable();
            $table->timestamp("end_time")->nullable();
            $table->integer('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consult_rule_schedules');
    }
}
