<?php

namespace App\Support;

class ModuleRegistry
{
    public const CORE_MODULES = [
        "core_auth",
        "core_profile",
        "core_subscriptions",
        "core_payments",
        "core_admin"
    ];

    public static function isCore(string $module): bool
    {
        return in_array($module, self::CORE_MODULES);
    }
}
