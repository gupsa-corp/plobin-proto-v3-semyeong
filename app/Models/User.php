<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Services\PhoneNumberHelper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'email',
        'password',
        'country_code',
        'phone_number',
        'nickname',
        'first_name',
        'last_name',
        'name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? $this->display_name;
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->nickname) {
            return $this->nickname;
        }

        $fullName = $this->full_name;
        if ($fullName) {
            return $fullName;
        }

        return explode('@', $this->email)[0];
    }

    public function getPhoneNumberInstance(): ?PhoneNumber
    {
        return PhoneNumberHelper::createPhoneNumber($this->phone_number ?? '', $this->country_code ?? '');
    }


    public function getFormattedPhoneAttribute(): ?string
    {
        return PhoneNumberHelper::formatNational($this->phone_number ?? '', $this->country_code ?? '') ?? $this->phone_number;
    }

    public function getE164PhoneAttribute(): ?string
    {
        return PhoneNumberHelper::formatE164($this->phone_number ?? '', $this->country_code ?? '');
    }

    public function getInternationalPhoneAttribute(): ?string
    {
        return PhoneNumberHelper::formatInternational($this->phone_number ?? '', $this->country_code ?? '');
    }

    public function isValidPhone(): bool
    {
        return PhoneNumberHelper::isValid($this->phone_number ?? '', $this->country_code ?? '');
    }

    public function getPhoneTypeAttribute(): ?string
    {
        return PhoneNumberHelper::getPhoneType($this->phone_number ?? '', $this->country_code ?? '');
    }

    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
                    ->withPivot(['permission_level', 'joined_at', 'invited_at', 'invitation_status'])
                    ->withTimestamps();
    }
}
