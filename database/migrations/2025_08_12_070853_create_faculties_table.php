<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();

            // ✅ Link to users table
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // ✅ Employee ID (unique identifier)
            $table->string('employee_id')->unique();

            // ✅ Department (you can remove default if you'll assign manually)
            $table->string('department')->default('Social Work');

            // ✅ Preferences (json column for flexible storage: teaching prefs, load, etc.)
            $table->json('preferences')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
