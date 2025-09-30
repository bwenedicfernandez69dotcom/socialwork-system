<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // 🆕 Add delivery mode (nullable for backward compatibility)
            $table->enum('mode', ['Face-to-Face', 'Online', 'Modular'])
                  ->nullable()
                  ->after('end_time');

            // 🆕 Add semester (optional)
            $table->enum('semester', ['1st', '2nd'])
                  ->nullable()
                  ->after('mode');

            // 🆕 Add school year (YYYY-YYYY format)
            $table->string('school_year')
                  ->nullable()
                  ->after('semester');
        });
    }

    public function down()
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn(['mode', 'semester', 'school_year']);
        });
    }
};
