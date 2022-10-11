<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedInteger('consultation_id');
            $table->foreign('consultation_id')
                ->references('id')
                ->on('consultations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('prescription')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->enum('payment_status', ['init','waiting','approve','reject']);
            $table->text('payment_reject_reason')->nullable();
            $table->enum('status', ['init', 'payment_process', 'consult_process', 'done']);
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
        Schema::dropIfExists('transactions');
    }
}
