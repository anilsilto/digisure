<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('tc_no');                       // encrypted
            $table->string('tc_hash')->unique();         // HMAC, aranabilir
            $table->text('phone');                       // encrypted
            $table->string('phone_hash')->index();       // HMAC, aranabilir
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->timestamp('kvkk_consent_at')->nullable();
            $table->timestamp('marketing_consent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
