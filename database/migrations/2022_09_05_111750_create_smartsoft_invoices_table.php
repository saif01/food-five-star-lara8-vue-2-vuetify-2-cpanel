<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmartsoftInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smartsoft_invoices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('INVOICE_DATE')->nullable();
            $table->string('INVOICE_NO')->nullable();
            $table->string('CUSTOMER_CODE')->nullable();
            $table->string('PRODUCT_CODE')->nullable();
            $table->integer('QUANTITY')->nullable();
            $table->double('WEIGHT',15,2)->nullable();
            $table->double('AMOUNT',15,2)->nullable();
            $table->double('VAT',15,2)->nullable();
            $table->double('NET_AMOUNT',15,2)->nullable();
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
        Schema::dropIfExists('smartsoft_invoices');
    }
}
