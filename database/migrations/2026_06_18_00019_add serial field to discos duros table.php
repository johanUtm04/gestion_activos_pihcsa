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
        Schema::table('discos_duros', function (Blueprint $table) {
            // Check to prevent duplication errors if ran multiple times
            if (!Schema::hasColumn('discos_duros', 'serial')) {
                $table->string('serial')->nullable()->after('interface');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discos_duros', function (Blueprint $table) {
            $table->dropColumn('serial');
        });
    }
};