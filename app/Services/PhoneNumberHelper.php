<?php

namespace App\Services;

use libphonenumber\PhoneNumberUtil;
use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneNumberHelper
{
    /**
     * Get country code in ISO 3166-1 alpha-2 format from phone country code.
     */
    public static function getCountryCodeForPhone(string $countryCode): string
    {
        try {
            // libphonenumber를 사용해서 국가 코드에서 ISO 국가 코드 추출
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCodeNumber = ltrim($countryCode, '+');
            $regions = $phoneUtil->getRegionCodesForCountryCode((int) $countryCodeNumber);
            
            // 첫 번째 지역 코드 반환 (가장 주요한 국가)
            return !empty($regions) ? $regions[0] : 'US';
        } catch (\Exception $e) {
            // 예외 발생 시 기본값 반환
            return 'US';
        }
    }

    /**
     * Create PhoneNumber instance from phone number and country code.
     */
    public static function createPhoneNumber(string $phoneNumber, string $countryCode): ?PhoneNumber
    {
        if (empty($phoneNumber) || empty($countryCode)) {
            return null;
        }

        try {
            return new PhoneNumber($phoneNumber, self::getCountryCodeForPhone($countryCode));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validate phone number format.
     */
    public static function isValid(string $phoneNumber, string $countryCode): bool
    {
        $phoneNumberInstance = self::createPhoneNumber($phoneNumber, $countryCode);
        
        if (!$phoneNumberInstance) {
            return false;
        }

        try {
            return $phoneNumberInstance->isValid();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format phone number in national format.
     */
    public static function formatNational(string $phoneNumber, string $countryCode): ?string
    {
        $phoneNumberInstance = self::createPhoneNumber($phoneNumber, $countryCode);
        
        if (!$phoneNumberInstance) {
            return null;
        }

        try {
            return $phoneNumberInstance->formatNational();
        } catch (\Exception $e) {
            return $phoneNumber;
        }
    }

    /**
     * Format phone number in E164 format.
     */
    public static function formatE164(string $phoneNumber, string $countryCode): ?string
    {
        $phoneNumberInstance = self::createPhoneNumber($phoneNumber, $countryCode);
        
        if (!$phoneNumberInstance) {
            return null;
        }

        try {
            return $phoneNumberInstance->formatE164();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format phone number in international format.
     */
    public static function formatInternational(string $phoneNumber, string $countryCode): ?string
    {
        $phoneNumberInstance = self::createPhoneNumber($phoneNumber, $countryCode);
        
        if (!$phoneNumberInstance) {
            return null;
        }

        try {
            return $phoneNumberInstance->formatInternational();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get phone number type (mobile, fixed line, etc).
     */
    public static function getPhoneType(string $phoneNumber, string $countryCode): ?string
    {
        $phoneNumberInstance = self::createPhoneNumber($phoneNumber, $countryCode);
        
        if (!$phoneNumberInstance) {
            return null;
        }

        try {
            $type = $phoneNumberInstance->getType();
            
            // PhoneNumberType enum을 문자열로 변환
            if ($type === \libphonenumber\PhoneNumberType::MOBILE) {
                return 'mobile';
            } elseif ($type === \libphonenumber\PhoneNumberType::FIXED_LINE) {
                return 'fixed_line';
            } elseif ($type === \libphonenumber\PhoneNumberType::FIXED_LINE_OR_MOBILE) {
                return 'fixed_line_or_mobile';
            } elseif ($type === \libphonenumber\PhoneNumberType::TOLL_FREE) {
                return 'toll_free';
            } elseif ($type === \libphonenumber\PhoneNumberType::PREMIUM_RATE) {
                return 'premium_rate';
            } elseif ($type === \libphonenumber\PhoneNumberType::SHARED_COST) {
                return 'shared_cost';
            } elseif ($type === \libphonenumber\PhoneNumberType::VOIP) {
                return 'voip';
            } elseif ($type === \libphonenumber\PhoneNumberType::PERSONAL_NUMBER) {
                return 'personal_number';
            } elseif ($type === \libphonenumber\PhoneNumberType::PAGER) {
                return 'pager';
            } elseif ($type === \libphonenumber\PhoneNumberType::UAN) {
                return 'uan';
            } elseif ($type === \libphonenumber\PhoneNumberType::VOICEMAIL) {
                return 'voicemail';
            } else {
                return 'unknown';
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all supported country codes with their regions.
     * 프론트엔드에서 사용할 수 있도록 JSON 형태로 반환
     */
    public static function getSupportedCountryCodes(): array
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $supportedRegions = $phoneUtil->getSupportedRegions();
            $countryCodes = [];

            foreach ($supportedRegions as $region) {
                $countryCode = $phoneUtil->getCountryCodeForRegion($region);
                if ($countryCode && $countryCode > 0) {
                    $countryCodes[] = [
                        'region' => $region,
                        'country_code' => '+' . $countryCode,
                        'country_code_number' => $countryCode
                    ];
                }
            }

            // 국가 코드별로 정렬
            usort($countryCodes, function($a, $b) {
                return $a['country_code_number'] <=> $b['country_code_number'];
            });

            return $countryCodes;
        } catch (\Exception $e) {
            return [];
        }
    }
}