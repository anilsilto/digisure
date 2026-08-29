<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_type_id')->constrained();
            $table->string('insurer');
            $table->string('policy_no');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('premium', 12, 2);
            $table->foreignId('quote_id')->nullable()->constrained('quotes')->nullOnDelete();
            $table->string('file_path')->nullable();
            $table->string('status')->default('aktif');   // aktif|yenilendi|iptal|suresi_doldu
            $table->foreignId('renewed_from_policy_id')->nullable()->constrained('policies')->nullOnDelete();
            $table->timestamps();

            $table->index('end_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
