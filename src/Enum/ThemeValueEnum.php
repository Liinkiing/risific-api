<?php


namespace App\Enum;


abstract class ThemeValueEnum
{
    public const LIGHT_THEME = 'light';
    public const DARK_THEME = 'dark';

    protected static $typeName = [
        self::LIGHT_THEME => 'Clair',
        self::DARK_THEME => 'Foncé',
    ];

    public static function getThemeValue($themeShortName): string
    {
        if (!isset(static::$typeName[$themeShortName])) {
            throw new \RuntimeException("Unknown theme type $themeShortName");
        }

        return static::$typeName[$themeShortName];
    }

    public static function isThemeValueValid($themeShortName): bool
    {
        return isset(static::$typeName[$themeShortName]);
    }

    public static function getAvailableThemeValues(): array
    {
        return [
            self::LIGHT_THEME,
            self::DARK_THEME,
        ];
    }

}