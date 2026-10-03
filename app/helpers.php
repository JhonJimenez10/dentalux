<?php

if (!function_exists('highlightSearch')) {
    function highlightSearch(string $text, string $search): string
    {
        if (empty($search)) return e($text);

        $terms = preg_split('/\s+/', trim($search));
        $result = e($text);

        foreach ($terms as $term) {
            if (strlen($term) < 2) continue;
            $pattern = '/(' . preg_quote(e($term), '/') . ')/iu';
            $result = preg_replace(
                $pattern,
                '<mark class="search-highlight">$1</mark>',
                $result
            );
        }

        return $result;
    }
}