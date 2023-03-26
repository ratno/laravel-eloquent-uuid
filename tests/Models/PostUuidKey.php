<?php

namespace Ratno\LaravelEloquentUuid\Tests\Models;

class PostUuidKey extends PostUuidAttribute
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'uuid';

    public function getUuidName(): string
    {
        return $this->getKeyName();
    }
}
