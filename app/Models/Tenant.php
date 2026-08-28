<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country_id',
        'currency_id',
        'tax_number',
        'active',
    ];

    /**
     * These are real columns on the `tenants` table. VirtualColumn (used by the
     * base Tenant model) moves every non-custom attribute into the `data` JSON
     * column on save, so they must be registered here to stay as real columns.
     */
    public static function getCustomColumns(): array
    {
        return array_merge(parent::getCustomColumns(), [
            'name',
            'email',
            'phone',
            'country_id',
            'currency_id',
            'tax_number',
            'active',
        ]);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    function country(){
        return Country::where('id',$this['country_id']??null)->first();
    }

    protected function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
        $this->syncDataAttribute('name', $value);
    }

    protected function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value;
        $this->syncDataAttribute('email', $value);
    }

    protected function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value;
        $this->syncDataAttribute('phone', $value);
    }

    protected function setCountryIdAttribute($value): void
    {
        $this->attributes['country_id'] = $value;
        $this->syncDataAttribute('country_id', $value);
    }

    protected function setCurrencyIdAttribute($value): void
    {
        $this->attributes['currency_id'] = $value;
        $this->syncDataAttribute('currency_id', $value);
    }

    protected function setTaxNumberAttribute($value): void
    {
        $this->attributes['tax_number'] = $value;
        $this->syncDataAttribute('tax_number', $value);
    }

    protected function setActiveAttribute($value): void
    {
        $this->attributes['active'] = $value;
        $this->syncDataAttribute('active', $value);
    }

    protected function syncDataAttribute(string $key, $value): void
    {
        $data = json_decode($this->attributes['data'] ?? '{}', true) ?: [];
        $data[$key] = $value;
        $this->attributes['data'] = json_encode($data);
    }

    /**
     * VirtualColumn::encodeAttributes() runs on every save/create/update and
     * rebuilds the `data` column from scratch, which would wipe out the sync
     * performed by the setXAttribute() mutators above. Re-apply it here, after
     * the base implementation has run, so `data` always reflects the current
     * value of the flat columns regardless of how they were set (mutator,
     * fill(), mass assignment, etc.).
     */
    protected function encodeAttributes(): void
    {
        parent::encodeAttributes();

        $data = json_decode($this->attributes[static::getDataColumn()] ?? '{}', true) ?: [];

        foreach (['name', 'email', 'phone', 'country_id', 'currency_id', 'tax_number', 'active'] as $column) {
            if (array_key_exists($column, $this->attributes)) {
                $data[$column] = $this->attributes[$column];
            }
        }

        $this->attributes[static::getDataColumn()] = json_encode($data);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeByCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(fn ($q) => $q->where('name', 'LIKE', "%{$term}%")->orWhere('email', 'LIKE', "%{$term}%"));
    }
}
