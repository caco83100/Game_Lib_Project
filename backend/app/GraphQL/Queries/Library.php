<?php

namespace App\GraphQL\Queries;

use App\Models\Library as LibraryModel; // alias: the class name is already taken by this resolver

final class Library
{
    /** @param array{id: string} $args */
    public function __invoke(mixed $root, array $args): ?LibraryModel
    {
        // null when the library does not exist: the `library` field is nullable
        return LibraryModel::find($args['id']);
    }
}
