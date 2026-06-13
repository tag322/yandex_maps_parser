<?php

namespace App\Support;

class Yandex
{
    public static function extractOid(string $url): ?int
    {
        $query = parse_url($url, PHP_URL_QUERY);

        if (!$query) {
            return null;
        }

        parse_str($query, $params);

        if (empty($params['poi']['uri'])) {
            return null;
        }

        $poiQuery = parse_url($params['poi']['uri'], PHP_URL_QUERY);

        if (!$poiQuery) {
            return null;
        }

        parse_str($poiQuery, $poiParams);

        return isset($poiParams['oid'])
            ? (int) $poiParams['oid']
            : null;
    }
}