<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'birth_year')) {
                $table->dropColumn('birth_year');
            }
            $table->date('birth_date')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'birth_date')) {
                $table->dropColumn('birth_date');
            }
            $table->unsignedSmallInteger('birth_year')->nullable()->after('photo');
        });
    }
};
