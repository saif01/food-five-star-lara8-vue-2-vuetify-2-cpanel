<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('product_code', 100)->nullable();
            $table->string('type')->nullable();
            $table->string('type_bn')->nullable();
            $table->string('category')->nullable();
            $table->string('packaging_type')->nullable();
            $table->double('weight')->nullable();
            $table->string('weight_type')->nullable();
            $table->integer('quantity')->nullable();
            $table->double('tp')->nullable();
            $table->double('mrp')->nullable();
            $table->double('gp')->nullable();
            $table->double('gp_percentage')->nullable();
            $table->string('remark')->nullable();
            $table->integer('status')->nullable();
            $table->integer('status_by')->nullable();
            $table->integer('deleted_temp')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('product_orders');
    }
}
