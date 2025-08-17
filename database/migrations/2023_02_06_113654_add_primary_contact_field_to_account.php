<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_contact_id')->nullable();
            $table->foreign('primary_contact_id')->references('id')->on('contacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                // For SQLite, we need to drop the foreign key constraint first
                $table->dropForeign(['primary_contact_id']);
            }
            $table->dropColumn('primary_contact_id');
        });
    }
};
