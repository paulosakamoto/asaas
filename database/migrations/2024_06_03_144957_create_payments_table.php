<?php

use App\Enums\BillingType;
use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('billing_type', BillingType::values());
            $table->enum('status', PaymentStatus::values())->default(PaymentStatus::PENDING->value);
            $table->decimal('value', 12,2);
            $table->date('due_date');
            $table->string('asaas_id', 50)->nullable();
            $table->timestamps();

            $table->index('asaas_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
