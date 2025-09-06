<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessInfo extends Model
{
    protected $fillable = [
        'organization_id',
        'business_name',
        'business_registration_number',
        'representative_name',
        'business_type',
        'business_item',
        'postal_code',
        'address',
        'detail_address',
        'phone',
        'fax',
        'email',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getFullAddress(): string
    {
        $address = $this->address;
        if ($this->detail_address) {
            $address .= ' ' . $this->detail_address;
        }
        return $address;
    }

    public function getFormattedBusinessNumber(): string
    {
        $number = $this->business_registration_number;
        if (strlen($number) === 10) {
            return substr($number, 0, 3) . '-' . substr($number, 3, 2) . '-' . substr($number, 5);
        }
        return $number;
    }

    public function hasCompleteInfo(): bool
    {
        return !empty($this->business_name) &&
               !empty($this->business_registration_number) &&
               !empty($this->representative_name) &&
               !empty($this->address);
    }
}
