<?php

namespace Ratno\LaravelEloquentUuid\Tests\Models;

use Ratno\LaravelEloquentUuid\Eloquent\Concerns\UsesUUID;
use Illuminate\Database\Eloquent\Model;

class PostUuidAttribute extends Model
{
    use UsesUUID;

    protected $table = 'posts';

    protected $guarded = [];
}
