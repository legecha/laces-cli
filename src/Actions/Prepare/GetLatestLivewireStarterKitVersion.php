<?php

declare(strict_types=1);

namespace Laces\Actions\Prepare;

use Laces\DataTransferObjects\Prepare\GetLatestLivewireStarterKitVersionDto;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class GetLatestLivewireStarterKitVersion
{
    /**
     * Get the latest Livewire Starter Kit version.
     */
    public static function run(): GetLatestLivewireStarterKitVersionDto
    {
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://api.github.com/repos/laravel/livewire-starter-kit/releases/latest');
            $data = $response->toArray();

            return new GetLatestLivewireStarterKitVersionDto(version: $data['tag_name']);
        } catch (Throwable $t) {
            return new GetLatestLivewireStarterKitVersionDto(error: $t->getMessage());
        }
    }
}
