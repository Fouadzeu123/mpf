<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('videos')->where('category', 'predication')->update(['category' => 'enseignement']);
        DB::table('videos')->where('category', 'prieres')->update(['category' => 'priere']);
        DB::table('videos')->where('category', 'temoignages')->update(['category' => 'temoignage']);
    }

    public function down(): void
    {
        DB::table('videos')->where('category', 'enseignement')->update(['category' => 'predication']);
        DB::table('videos')->where('category', 'priere')->update(['category' => 'prieres']);
        DB::table('videos')->where('category', 'temoignage')->update(['category' => 'temoignages']);
    }
};
