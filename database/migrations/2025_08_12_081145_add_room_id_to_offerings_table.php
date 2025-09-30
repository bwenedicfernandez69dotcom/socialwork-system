<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('offerings', function (Blueprint $table) {
        if (!Schema::hasColumn('offerings', 'room_id')) {
            $table->integer('room_id')->nullable(false);
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('offerings', function (Blueprint $table) {
        $table->dropForeign(['room_id']);
        $table->dropColumn('room_id');
    });
}
};
