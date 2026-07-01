<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    public static function diffInHoursSql(string $start, string $end): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $start = self::quoteCamelCase($start);
            $end   = self::quoteCamelCase($end);
            return "EXTRACT(EPOCH FROM ({$end} - {$start})) / 3600";
        }

        return "TIMESTAMPDIFF(HOUR, {$start}, {$end})";
    }

    private static function quoteCamelCase(string $name): string
    {
        if (!preg_match('/[A-Z]/', $name)) return $name;

        if (str_contains($name, '.')) {
            [$table, $column] = explode('.', $name, 2);
            return $table . '."' . $column . '"';
        }
        return '"' . $name . '"';
    }
}
