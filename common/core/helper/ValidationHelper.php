<?php

namespace common\core\helper;

use libphonenumber\PhoneNumberUtil;

class ValidationHelper
{
    public static function isValidIsbn10(?string $isbn = null): bool
    {
        if (!$isbn) {
            return false;
        }

        $isbn = str_replace(['-', ' '], '', $isbn); // Remove hyphens and spaces

        if (strlen($isbn) !== 10) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            if (!is_numeric($isbn[$i])) {
                return false; // Ensure first 9 characters are digits
            }
            $sum += (int)$isbn[$i] * (10 - $i);
        }

        $lastChar = strtoupper($isbn[9]);
        if ($lastChar === 'X') {
            $sum += 10 * 1; // Weight for the 10th digit is 1
        } elseif (is_numeric($lastChar)) {
            $sum += (int)$lastChar * 1;
        } else {
            return false; // Last character is neither a digit nor 'X'
        }

        return ($sum % 11 === 0);
    }

    public static function isValidPhoneNumber(?int $phone = null): bool
    {
        $result = false;

        if($phone) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            try {
                $containPhone = $phoneNumberUtil->parse($phone, "RU");
                $result = $phoneNumberUtil->isValidNumber($containPhone);
            } catch (\Throwable $e) {

            }
        }

        return $result;
    }
}