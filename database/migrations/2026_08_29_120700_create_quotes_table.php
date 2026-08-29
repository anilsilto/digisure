<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_request_id')->constrained()->cascadeOnDelete();
            $table->string('insurer');                                   // sompo|quick|hepiyi|doga
            $table->string('status')->default('beklemede');              // beklemede|verildi|reddedildi
            $table->decimal('premium', 12, 2)->nullable();
            $table->json('coverage_summary')->nullable();
            $table->unsignedSmallInteger('policy_period_months')->nullable();
            $table->string('insurer_quote_no')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('origin')->default('manuel');                 // api|manuel
            $table->string('file_path')->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
