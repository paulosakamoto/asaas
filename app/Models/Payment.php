<?php

namespace App\Models;

use App\Enums\BillingType;
use App\Enums\PaymentStatus;
use App\HasAsaasId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Payment extends Model
{
    use HasFactory, HasAsaasId;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'billing_type',
        'status',
        'value',
        'due_date',
        'asaas_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'billing_type' => BillingType::class,
            'status' => PaymentStatus::class,
            'due_date' => 'datetime:Y-m-d',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return string
     */
    public function dueDateFormatted(): string
    {
        return $this->due_date?->format('Y-m-d')?:'';
    }

    /**
     * @return array
     */
    public static function availableBillingTypes(): array
    {
        return [
            BillingType::BOLETO->value => BillingType::BOLETO->value,
            BillingType::PIX->value => BillingType::PIX->value,
            BillingType::CREDIT_CARD->value => BillingType::CREDIT_CARD->value,
        ];
    }
}
