<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Order: string implements HasColor, HasIcon, HasLabel
{



    case Invoiced = 'invoiced';

    case Collected = 'Collected';

    case Shipped = 'shipped';

    case Delivered = 'delivered';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Invoiced => 'مفوتره',
            self::Collected => 'تم التحصيل',
            self::Shipped => 'جاري الشحن',
            self::Delivered => 'تم الاستلام',
            self::Cancelled => 'تم الالغاء',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Shipped, self::Delivered  => 'info',
            self::Collected , self::Invoiced => 'success',
            self::Cancelled => 'danger',
        };
    }



    public function getIcon(): ?string
    {
        return match ($this) {
            self::Invoiced => 'heroicon-m-newspaper',
            self::Collected => 'heroicon-m-banknotes',
            self::Shipped => 'heroicon-m-truck',
            self::Delivered => 'heroicon-m-check-badge',
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }
}
