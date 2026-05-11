<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update exchange_rates table
        Schema::table('exchange_rates', function (Blueprint $table) {
            if (!Schema::hasColumn('exchange_rates', 'change_percentage')) {
                $table->decimal('change_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('exchange_rates', 'status')) {
                $table->boolean('status')->default(true);
            }
            if (!Schema::hasColumn('exchange_rates', 'is_popular')) {
                $table->boolean('is_popular')->default(false);
            }
        });

        // Table for Rate Page Settings (CMS)
        Schema::create('rate_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Tỷ giá ngoại tệ thời gian thực');
            $table->text('hero_description')->nullable();
            $table->string('cta_text')->default('Tính toán ngay');
            $table->string('default_currency')->default('USD');
            $table->string('banner_image')->nullable();
            $table->string('display_effect')->default('glass');
            $table->timestamps();
        });

        // Table for Market News/AI Analysis
        Schema::create('market_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type'); // trend, opportunity, warning, news
            $table->string('severity')->default('info'); // info, success, warning, danger
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_news');
        Schema::dropIfExists('rate_page_settings');
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropColumn(['change_percentage', 'status', 'is_popular']);
        });
    }
};
