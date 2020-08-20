<?php

namespace Tests\Unit;

use App\Comment;
use App\Library;
use App\Manga;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

/**
 * @covers \App\User
 * @covers \App\Policies\CommentPolicy
 */
class CommentPolicyTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @var Manga */
    private $manga = null;

    /** @var User */
    private $admin = null;
    /** @var Comment */
    private $adminComment = null;
    /** @var User */
    private $moderator = null;
    /** @var Comment */
    private $moderatorComment = null;
    /** @var User */
    private $editor = null;
    /** @var Comment */
    private $editorComment = null;
    /** @var User */
    private $member = null;
    /** @var Comment */
    private $memberComment = null;
    /** @var User */
    private $banned = null;
    /** @var Comment */
    private $bannedComment = null;
    /** @var CommentPolicy $commentsPolicy */
    private $commentsPolicy = null;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->seed([
            \PermissionsTableSeeder::class,
            \RolesTableSeeder::class
        ]);

        $this->manga = factory(Manga::class)->create([
            'library_id' => factory(Library::class)->create()
        ]);

        $this->admin = factory(User::class)->create();
        $this->moderator = factory(User::class)->create();
        $this->editor = factory(User::class)->create();
        $this->member = factory(User::class)->create();
        $this->banned = factory(User::class)->create();

        $this->admin->grantRole('Administrator');
        $this->moderator->grantRole('Moderator');
        $this->editor->grantRole('Editor');
        $this->member->grantRole('Member');
        $this->banned->grantRole('Banned');

        $this->commentsPolicy = policy(Comment::class);

        $this->adminComment = $this->admin->comments()->create([
            'text' => $this->faker->sentence(),
            'manga_id' => $this->manga->id
        ]);
        $this->moderatorComment = $this->admin->comments()->create([
            'text' => $this->faker->sentence(),
            'manga_id' => $this->manga->id
        ]);
        $this->editorComment = $this->admin->comments()->create([
            'text' => $this->faker->sentence(),
            'manga_id' => $this->manga->id
        ]);
        $this->memberComment = $this->admin->comments()->create([
            'text' => $this->faker->sentence(),
            'manga_id' => $this->manga->id
        ]);
        $this->bannedComment = $this->admin->comments()->create([
            'text' => $this->faker->sentence(),
            'manga_id' => $this->manga->id
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testView()
    {
        /** @var Comment $randomComment */
        $randomComment = factory(Comment::class)->create();

        $this->assertTrue($this->commentsPolicy->view($this->admin, $randomComment));
        $this->assertTrue($this->commentsPolicy->view($this->moderator, $randomComment));
        $this->assertTrue($this->commentsPolicy->view($this->editor, $randomComment));
        $this->assertTrue($this->commentsPolicy->view($this->member, $randomComment));
        $this->assertFalse($this->commentsPolicy->view($this->banned, $randomComment));
    }

    public function testCreate()
    {
        $this->assertTrue($this->commentsPolicy->create($this->admin));
        $this->assertTrue($this->commentsPolicy->create($this->moderator));
        $this->assertTrue($this->commentsPolicy->create($this->editor));
        $this->assertTrue($this->commentsPolicy->create($this->member));
        $this->assertFalse($this->commentsPolicy->create($this->banned));
    }

    public function testDeleteRandom()
    {
        $randomComment = factory(Comment::class)->create();

        $this->assertTrue($this->commentsPolicy->delete($this->admin, $randomComment));
        $this->assertTrue($this->commentsPolicy->delete($this->moderator, $randomComment));
        $this->assertFalse($this->commentsPolicy->delete($this->editor, $randomComment));
        $this->assertFalse($this->commentsPolicy->delete($this->member, $randomComment));
        $this->assertFalse($this->commentsPolicy->delete($this->banned, $randomComment));
    }

    public function testDeleteOwn()
    {
        $this->assertTrue($this->commentsPolicy->delete($this->admin, $this->adminComment));
        $this->assertTrue($this->commentsPolicy->delete($this->moderator, $this->moderatorComment));
        $this->assertFalse($this->commentsPolicy->delete($this->editor, $this->editorComment));
        $this->assertFalse($this->commentsPolicy->delete($this->member, $this->memberComment));
        $this->assertFalse($this->commentsPolicy->delete($this->banned, $this->bannedComment));
    }

    public function testForceDeleteRandom()
    {
        $randomComment = factory(Comment::class)->create();

        $this->assertTrue($this->commentsPolicy->forceDelete($this->admin, $randomComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->moderator, $randomComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->editor, $randomComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->member, $randomComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->banned, $randomComment));
    }

    public function testForceDeleteOwn()
    {
        $this->assertTrue($this->commentsPolicy->forceDelete($this->admin, $this->adminComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->moderator, $this->moderatorComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->editor, $this->editorComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->member, $this->memberComment));
        $this->assertFalse($this->commentsPolicy->forceDelete($this->banned, $this->bannedComment));
    }

    public function testRestoreRandom()
    {
        $randomComment = factory(Comment::class)->create();

        $this->assertTrue($this->commentsPolicy->restore($this->admin, $randomComment));
        $this->assertTrue($this->commentsPolicy->restore($this->moderator, $randomComment));
        $this->assertFalse($this->commentsPolicy->restore($this->editor, $randomComment));
        $this->assertFalse($this->commentsPolicy->restore($this->member, $randomComment));
        $this->assertFalse($this->commentsPolicy->restore($this->banned, $randomComment));
    }

    public function testRestoreOwn()
    {
        $this->assertTrue($this->commentsPolicy->restore($this->admin, $this->adminComment));
        $this->assertTrue($this->commentsPolicy->restore($this->moderator, $this->moderatorComment));
        $this->assertFalse($this->commentsPolicy->restore($this->editor, $this->editorComment));
        $this->assertFalse($this->commentsPolicy->restore($this->member, $this->memberComment));
        $this->assertFalse($this->commentsPolicy->restore($this->banned, $this->bannedComment));
    }

    public function testUpdateRandom()
    {
        $randomComment = factory(Comment::class)->create();

        $this->assertTrue($this->commentsPolicy->update($this->admin, $randomComment));
        $this->assertTrue($this->commentsPolicy->update($this->moderator, $randomComment));
        $this->assertFalse($this->commentsPolicy->update($this->editor, $randomComment));
        $this->assertFalse($this->commentsPolicy->update($this->member, $randomComment));
        $this->assertFalse($this->commentsPolicy->update($this->banned, $randomComment));
    }

    public function testUpdateOwn()
    {
        $this->assertTrue($this->commentsPolicy->update($this->admin, $this->adminComment));
        $this->assertTrue($this->commentsPolicy->update($this->moderator, $this->moderatorComment));
        $this->assertFalse($this->commentsPolicy->update($this->editor, $this->editorComment));
        $this->assertFalse($this->commentsPolicy->update($this->member, $this->memberComment));
        $this->assertFalse($this->commentsPolicy->update($this->banned, $this->bannedComment));
    }
}
