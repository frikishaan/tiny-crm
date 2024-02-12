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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->integer('source')->nullable();
            $table->double('estimated_revenue', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1); // Prospect
            $table->integer('disqualification_reason')->nullable();
            $table->text('disqualification_description')->nullable();
            $table->dateTime('date_disqualified')->nullable();
            $table->dateTime('date_qualified')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('accounts')->nullOnDelete();

            $table->index(['status', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
