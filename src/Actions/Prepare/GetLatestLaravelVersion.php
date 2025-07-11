<?php

declare(strict_types=1);

namespace Laces\Actions\Prepare;

use Laces\DataTransferObjects\Prepare\GetLatestLaravelVersionDto;
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

            return new GetLatestLaravelVersionDto(version: $data['tag_name']);
        } catch (Throwable $t) {
            return new GetLatestLaravelVersionDto(error: $t->getMessage());
        }
    }
}
