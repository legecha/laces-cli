<?php

declare(strict_types=1);

namespace Laces\Actions;

use Laces\DataTransferObjects\GetLatestLacesVersionsDto;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class GetLatestLacesVersions
{
    /**
     * Get the Laravel and Livewire Starter Kit versions used by the latest Laces starter kit.
     */
    public static function run(): GetLatestLacesVersionsDto
    {
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://raw.githubusercontent.com/legecha/laces/main/composer.json');
            $composer = json_decode($response->getContent(false), true);

            $extra = $composer['extra']['laces'] ?? null;

            return new GetLatestLacesVersionsDto(
                laravelVersion: $extra['laravel-version'] ?? null,
                livewireStarterKitVersion: $extra['livewire-starter-kit-version'] ?? null
            );
        } catch (Throwable $e) {
            return new GetLatestLacesVersionsDto(error: $e->getMessage());
        }
    }
}
