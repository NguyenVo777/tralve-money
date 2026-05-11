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
        // Update Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('users', 'language')) {
                $table->string('language')->default('en');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user'); // admin or user
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active'); // active or locked
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
        });

        // Create Scans Table (AI Scans)
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency')->nullable();
            $table->string('image_path');
            $table->text('result')->nullable();
            $table->decimal('accuracy', 5, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // Create Exchange Rates Table
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('currency_code', 3);
            $table->string('currency_name');
            $table->decimal('rate_to_usd', 15, 6);
            $table->string('flag_icon')->nullable();
            $table->timestamps();
        });

        // Create Exchange Rate History Table
        Schema::create('exchange_rate_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_rate_id')->constrained()->onDelete('cascade');
            $table->decimal('rate', 15, 6);
            $table->timestamps();
        });

        // Create Activity Logs Table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // scan, conversion, search, etc.
            $table->text('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // Create Login Logs Table
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('exchange_rate_histories');
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('scans');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'country', 'language', 'avatar', 'role', 'status', 'last_login_at']);
        });
    }
};
