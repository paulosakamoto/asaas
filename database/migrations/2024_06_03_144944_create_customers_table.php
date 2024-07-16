<?php

use App\Enums\PersonType;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('cpf_cnpj', 14)->nullable();
            $table->string('mobile_phone', 20)->nullable();
            $table->enum('person_type', PersonType::values())->nullable();
            $table->string('asaas_id', 50)->nullable();
            $table->timestamps();
            $table->index('asaas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
