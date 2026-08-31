<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quote_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');            // panel_goruntulendi | cta_tiklandi | talep_olusturuldu | capraz_police
            $table->string('product_key')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_events');
    }
};
