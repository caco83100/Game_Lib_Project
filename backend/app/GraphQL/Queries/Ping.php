<?php

namespace App\GraphQL\Queries;

final class Ping
{
    public function __invoke(): string
    {
       return 'pong';
    }
}
