<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductStatusEnum extends Enum
{
    const NGUNG_BAN =   0;
    const DANG_BAN =   1;
    const HET_HANG =   2;

    public static function getDescription($value): string
    {
        if ($value === self::NGUNG_BAN) {
            return 'Ngừng bán';
        }
        if ($value === self::DANG_BAN) {
            return 'Đang bán';
        }
        if ($value === self::HET_HANG) {
            return 'Hết hàng';
        }

        return ProductStatusEnum::getDescription($value);
    }
}
