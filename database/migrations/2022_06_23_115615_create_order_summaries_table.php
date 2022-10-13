<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('order_date')->nullable();
            $table->string('req_number')->nullable();
            $table->string('cv_code')->nullable();
            $table->float('total_price')->nullable();
            $table->float('payment_amount')->nullable();
            $table->dateTime('payment_prove')->nullable();
            $table->integer('manager_id')->nullable();
            $table->dateTime('manager_approve')->nullable();
            $table->integer('admin_id')->nullable();
            $table->dateTime('admin_approve')->nullable();
            $table->string('invoice_number')->nullable();
            $table->integer('invoice_by')->nullable();
            $table->dateTime('invoice_time')->nullable();

            $table->integer('status')->nullable();
            $table->integer('status_by')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('order_summaries');
    }
}
