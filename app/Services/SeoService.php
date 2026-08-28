<?php

namespace App\Services;

class SeoService
{
    /**
     * Build a complete HTML meta tags block for a page.
     *
     * Supported $params keys:
     *  - title (string)
     *  - description (string, auto-truncated to 160 chars)
     *  - keywords (string)
     *  - canonical (string URL)
     *  - og_title (string, falls back to title)
     *  - og_description (string, falls back to description)
     *  - og_image (string URL)
     *  - og_type (string, default 'website')
     *  - twitter_card (string, default 'summary_large_image')
     *  - schema (array, JSON-LD structured data)
     *  - locale (string, e.g. en_US or ar_EG)
     *  - hreflang (array of ['lang' => ..., 'url' => ...])
     *  - robots (string, e.g. 'noindex,nofollow')
     */
    public static function page(array $params): string
    {
        $title = trim((string) ($params['title'] ?? ''));
        if ($title !== '') {
            $title .= ' | Mohaaseb';
        }

        $description = self::truncate((string) ($params['description'] ?? ''), 160);
        $keywords = trim((string) ($params['keywords'] ?? ''));
        $canonical = trim((string) ($params['canonical'] ?? ''));

        $ogTitle = trim((string) ($params['og_title'] ?? '')) ?: $title;
        $ogDescription = trim((string) ($params['og_description'] ?? '')) ?: $description;
        $ogImage = trim((string) ($params['og_image'] ?? ''));
        $ogType = trim((string) ($params['og_type'] ?? '')) ?: 'website';
        $twitterCard = trim((string) ($params['twitter_card'] ?? '')) ?: 'summary_large_image';

        $locale = trim((string) ($params['locale'] ?? ''));
        $robots = trim((string) ($params['robots'] ?? ''));

        $schema = $params['schema'] ?? null;
        $hreflang = $params['hreflang'] ?? [];

        $lines = [];

        if ($title !== '') {
            $lines[] = '<title>' . e($title) . '</title>';
        }

        if ($description !== '') {
            $lines[] = '<meta name="description" content="' . e($description) . '">';
        }

        if ($keywords !== '') {
            $lines[] = '<meta name="keywords" content="' . e($keywords) . '">';
        }

        if ($robots !== '') {
            $lines[] = '<meta name="robots" content="' . e($robots) . '">';
        }

        if ($canonical !== '') {
            $lines[] = '<link rel="canonical" href="' . e($canonical) . '">';
        }

        if ($ogTitle !== '') {
            $lines[] = '<meta property="og:title" content="' . e($ogTitle) . '">';
        }

        if ($ogDescription !== '') {
            $lines[] = '<meta property="og:description" content="' . e($ogDescription) . '">';
        }

        if ($ogImage !== '') {
            $lines[] = '<meta property="og:image" content="' . e($ogImage) . '">';
        }

        if ($canonical !== '') {
            $lines[] = '<meta property="og:url" content="' . e($canonical) . '">';
        }

        if ($ogType !== '') {
            $lines[] = '<meta property="og:type" content="' . e($ogType) . '">';
        }

        if ($locale !== '') {
            $lines[] = '<meta property="og:locale" content="' . e($locale) . '">';
        }

        if ($twitterCard !== '') {
            $lines[] = '<meta name="twitter:card" content="' . e($twitterCard) . '">';
        }

        if ($ogTitle !== '') {
            $lines[] = '<meta name="twitter:title" content="' . e($ogTitle) . '">';
        }

        if ($ogDescription !== '') {
            $lines[] = '<meta name="twitter:description" content="' . e($ogDescription) . '">';
        }

        if ($ogImage !== '') {
            $lines[] = '<meta name="twitter:image" content="' . e($ogImage) . '">';
        }

        if (is_array($hreflang)) {
            foreach ($hreflang as $entry) {
                $lang = trim((string) ($entry['lang'] ?? ''));
                $url = trim((string) ($entry['url'] ?? ''));

                if ($lang === '' || $url === '') {
                    continue;
                }

                $lines[] = '<link rel="alternate" hreflang="' . e($lang) . '" href="' . e($url) . '">';
            }
        }

        if (is_array($schema) && $schema !== []) {
            $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($json !== false) {
                $lines[] = '<script type="application/ld+json">' . $json . '</script>';
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Word-boundary-safe truncation with ellipsis.
     */
    public static function truncate(string $value, int $limit = 160): string
    {
        $value = trim(strip_tags($value));

        if ($value === '') {
            return '';
        }

        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        $truncated = mb_substr($value, 0, $limit - 1);

        $lastSpace = mb_strrpos($truncated, ' ');
        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return rtrim($truncated) . '…';
    }
}
