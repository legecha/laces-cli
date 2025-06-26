<?php

declare(strict_types=1);

namespace Laces\Actions;

use Laces\DataTransferObjects\GetLatestLaravelVersionDto;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class GetLatestLaravelVersion
{
    /**
     * Get the latest Laravel Framework version.
     */
    public static function run(): GetLatestLaravelVersionDto
    {
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://api.github.com/repos/laravel/framework/releases/latest');
            $data = $response->toArray();

            return new GetLatestLaravelVersionDto(version: ltrim($data['tag_name'], 'v'));
        } catch (Throwable $t) {
            return new GetLatestLaravelVersionDto(error: $t->getMessage());
        }
    }
}
