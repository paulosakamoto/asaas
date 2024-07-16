<?php

namespace App\Models;

use App\Enums\PersonType;
use App\HasAsaasId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Customer extends Model
{
    use HasFactory, HasAsaasId;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf_cnpj',
        'mobile_phone',
        'person_type',
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
            'person_type' => PersonType::class,
        ];
    }

    /**
     * @return Attribute
     */
    public function cpfCnpj(): Attribute
    {
        return Attribute::make(
            set: fn($value) => preg_replace('/\D/', '', (string) $value),
        );
    }

    /**
     * @return bool
     */
    public function fisica(): bool
    {
        return $this->person_type === PersonType::FISICA;
    }

    /**
     * @return bool
     */
    public function juridica(): bool
    {
        return $this->person_type === PersonType::JURIDICA;
    }

    /**
     * @return Attribute
     */
    public function mobilePhone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => preg_replace('/\D/', '', $value),
        );
    }
}
