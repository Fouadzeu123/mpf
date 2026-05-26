<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('photo')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone')->nullable();
            $table->text('address_description')->nullable();
            $table->string('department')->nullable();
            $table->string('status')->default('active');
            $table->string('qr_code')->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->text('address_description')->nullable();
            $table->string('qr_code')->unique();
            $table->date('visit_date');
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_type');
            $table->timestamp('scanned_at');
            $table->index(['member_id', 'service_type', 'scanned_at']);
        });

        Schema::create('communion_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('verse_reference');
            $table->text('verse_text');
            $table->boolean('whatsapp_sent')->default(false);
            $table->string('payment_status')->default('free');
            $table->string('payment_reference')->nullable();
            $table->boolean('remote')->default(false);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('member_verse_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('verse_reference');
            $table->text('verse_text');
            $table->timestamps();
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->unsignedInteger('amount');
            $table->string('currency', 10)->default('XAF');
            $table->string('provider')->nullable();
            $table->string('status')->default('pending');
            $table->string('notchpay_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('member_verse_history');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('communion_preparations');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('members');
    }
};
