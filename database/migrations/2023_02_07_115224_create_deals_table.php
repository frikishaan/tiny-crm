<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->float('estimated_revenue')->nullable();
            $table->float('actual_revenue')->nullable();
            $table->integer('status')->default(1); // Open
            $table->dateTime('date_won')->nullable();
            $table->dateTime('date_lost')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('accounts');
            $table->foreign('lead_id')->references('id')->on('leads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
};
