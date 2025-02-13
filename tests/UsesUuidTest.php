<?php

namespace Ratno\LaravelEloquentUuid\Tests;

use Ratno\LaravelEloquentUuid\Tests\Models\PostUuidAttribute;
use Ratno\LaravelEloquentUuid\Tests\Models\PostUuidKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Orchestra\Testbench\TestCase;
use Ramsey\Uuid\Uuid;

final class UsesUuidTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /** @test */
    public function it_generates_uuid_on_creating()
    {
        $post = PostUuidAttribute::create([]);

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
    }

    /** @test */
    public function it_generates_uuid_on_creating_when_uuid_attribute_is_invalid()
    {
        $post = PostUuidAttribute::create([
            'uuid' => 'foobar',
        ]);

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
        static::assertNotEquals('foobar', $post->uuid);
    }

    /** @test */
    public function it_does_not_generate_uuid_on_creating_when_uuid_attribute_is_valid()
    {
        $uuid = Str::uuid()->toString();

        $post = new PostUuidAttribute();
        $post->setUuid($uuid)->save();

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
        static::assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function it_can_use_uuid_as_primary_key()
    {
        $uuid = Str::uuid()->toString();

        PostUuidKey::create([
            'uuid' => $uuid,
        ]);

        $post = PostUuidKey::whereKey($uuid)->firstOrFail();

        static::assertIsString($post->getUuid());
        static::assertIsString($post->getKey());
        static::assertTrue(Uuid::isValid($post->getKey()));
        static::assertSame($uuid, $post->getKey());
        static::assertSame($post->getUuid(), $post->getKey());
    }

    /** @test */
    public function it_can_query_by_single_uuid_scope()
    {
        $uuid = Str::uuid()->toString();

        PostUuidAttribute::create([
            'uuid' => $uuid,
        ]);

        $post = PostUuidAttribute::byUuid($uuid)->firstOrFail();

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
        static::assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function it_can_query_by_uuid_interface_scope()
    {
        $uuid = Str::uuid();

        PostUuidAttribute::create([
            'uuid' => $uuid->toString(),
        ]);

        $post = PostUuidAttribute::byUuid($uuid)->firstOrFail();

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
        static::assertSame($uuid->toString(), $post->uuid);
    }

    /** @test */
    public function it_can_query_by_multiple_uuid_scope()
    {
        $uuid = Str::uuid()->toString();

        PostUuidAttribute::create([
            'uuid' => $uuid,
        ]);

        $post = PostUuidAttribute::byUuid([$uuid, Str::uuid()])->firstOrFail();

        static::assertIsString($post->getUuid());
        static::assertTrue(Uuid::isValid($post->uuid));
        static::assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function it_throws_exception_when_invalid_type_passed_to_scope()
    {
        static::expectException(InvalidArgumentException::class);

        PostUuidAttribute::create([]);

        PostUuidAttribute::byUuid(1.5)->firstOrFail();
    }

    /** @test */
    public function it_throws_exception_when_invalid_uuid_set()
    {
        static::expectException(InvalidArgumentException::class);

        $post = new PostUuidAttribute();
        $post->setUuid('foobar');
    }
}
