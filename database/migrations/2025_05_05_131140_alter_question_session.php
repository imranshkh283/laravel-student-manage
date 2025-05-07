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
        Schema::table('questions_sessions', function (Blueprint $table) {
            $table->string('grade', 10)->nullable()->after('score');

            // Update enum type using raw SQL if using MySQL
            DB::statement("ALTER TABLE questions_sessions MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'abandoned', 'evaluated') NOT NULL");
        });
    }

    public function down()
    {
        Schema::table('questions_sessions', function (Blueprint $table) {
            $table->dropColumn('grade');

            // Revert enum to original (if needed)
            DB::statement("ALTER TABLE questions_sessions MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'abandoned') NOT NULL");
        });
    }
};
