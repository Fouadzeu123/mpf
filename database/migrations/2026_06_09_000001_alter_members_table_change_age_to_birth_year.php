<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'age')) {
                $table->dropColumn('age');
            }
            $table->unsignedSmallInteger('birth_year')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'birth_year')) {
                $table->dropColumn('birth_year');
            }
            $table->unsignedTinyInteger('age')->nullable()->after('photo');
        });
    }
};
