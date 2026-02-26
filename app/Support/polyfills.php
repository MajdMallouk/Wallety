<?php

if (! function_exists('mb_strimwidth')) {
    function mb_strimwidth(
        string $string,
        int $start,
        int $width,
        string $trimMarker = '',
        ?string $encoding = null,
    ): string {
        $encoding ??= 'UTF-8';
        $segment = mb_substr($string, $start, $width, $encoding);
        $fullLength = mb_strlen($string, $encoding);

        if ($fullLength > $start + $width && $trimMarker !== '') {
            return $segment.$trimMarker;
        }

        return $segment;
    }
}
