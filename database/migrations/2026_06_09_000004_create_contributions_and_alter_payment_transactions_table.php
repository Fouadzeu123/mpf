<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('type')->default('communion')->after('member_id');
            $table->foreignId('event_id')->nullable()->after('type')->constrained()->nullOnDelete();
        });

        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('payment_method')->default('mobile_money'); // mobile_money, manual
            $table->string('payment_reference')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'event_id')) {
                $table->dropForeign(['event_id']);
                $table->dropColumn('event_id');
            }
            if (Schema::hasColumn('payment_transactions', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
