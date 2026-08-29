<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renewal_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->cascadeOnDelete();
            $table->date('remind_on');
            $table->string('channel');                       // sms | eposta
            $table->string('status')->default('bekliyor');   // bekliyor | gonderildi | hata
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['policy_id', 'remind_on', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renewal_reminders');
    }
};
