<?php

namespace App\Actions\Links;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FetchLinkPreviewAction
{
    /**
     * @return array{url: string, title: string, description: string|null, image: string|null, site_name: string|null, domain: string|null}
     *
     * @throws RuntimeException
     */
    public function handle(string $url): array
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 LinkPreviewBot/1.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);
        } catch (ConnectionException) {
            throw new RuntimeException('Impossible de se connecter à cette URL.');
        }

        if (! $response->successful()) {
            throw new RuntimeException("La page a renvoyé le code {$response->status()}.");
        }

        return $this->extractMetadata($response->body(), $url);
    }

    /**
     * @return array{url: string, title: string, description: string|null, image: string|null, site_name: string|null, domain: string|null}
     */
    private function extractMetadata(string $html, string $url): array
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument;
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $metas = [];

        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $key = strtolower(
                $meta->getAttribute('property') ?: $meta->getAttribute('name')
            );
            $content = trim($meta->getAttribute('content'));

            if ($key && $content) {
                $metas[$key] = $content;
            }
        }

        $title = $metas['og:title']
            ?? $metas['twitter:title']
            ?? $this->getTitle($dom)
            ?? $url;

        $description = $metas['og:description']
            ?? $metas['twitter:description']
            ?? $metas['description']
            ?? null;

        $image = $metas['og:image'] ?? $metas['twitter:image'] ?? null;

        if ($image) {
            $image = $this->absoluteUrl($image, $url);
        }

        return [
            'url' => $url,
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'site_name' => $metas['og:site_name'] ?? parse_url($url, PHP_URL_HOST),
            'domain' => parse_url($url, PHP_URL_HOST),
        ];
    }

    private function getTitle(\DOMDocument $dom): ?string
    {
        $titles = $dom->getElementsByTagName('title');

        return $titles->length > 0 ? trim($titles->item(0)->textContent) : null;
    }

    private function absoluteUrl(string $path, string $baseUrl): string
    {
        if (preg_match('#^https?://#', $path)) {
            return $path;
        }

        $base = parse_url($baseUrl);
        $scheme = $base['scheme'] ?? 'https';
        $host = $base['host'] ?? '';

        if (str_starts_with($path, '//')) {
            return $scheme.':'.$path;
        }

        if (str_starts_with($path, '/')) {
            return $scheme.'://'.$host.$path;
        }

        return $scheme.'://'.$host.'/'.ltrim($path, '/');
    }
}
