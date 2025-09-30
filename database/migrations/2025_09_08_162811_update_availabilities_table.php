<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // Remove "mode" column if exists
            if (Schema::hasColumn('availabilities', 'mode')) {
                $table->dropColumn('mode');
            }

            // Ensure semester and school_year exist
            if (!Schema::hasColumn('availabilities', 'semester')) {
                $table->enum('semester', ['1st', '2nd'])->nullable()->after('end_time');
            }

            if (!Schema::hasColumn('availabilities', 'school_year')) {
                $table->string('school_year')->nullable()->after('semester');
            }

            // Ensure status exists
            if (!Schema::hasColumn('availabilities', 'status')) {
                $table->enum('status', ['available', 'unavailable'])->default('available')->after('school_year');
            }
        });
    }

    public function down()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // Rollback: add mode back
            $table->enum('mode', ['Face-to-Face', 'Online', 'Modular'])->nullable();

            // Drop new columns
            $table->dropColumn(['semester', 'school_year', 'status']);
        });
    }
};
