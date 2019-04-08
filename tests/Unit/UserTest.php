<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessibles_projects()
    {
        $john = $this->signIn();

        ProjectFactory::ownedBy($john)
            ->create();

        $this->assertCount(1, $john->accessibleProjects());

        [$sally, $nick] = factory(User::class, 2)->create();

        $project = ProjectFactory::ownedBy($sally)
            ->create();

        $project->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $project->invite($john);

        $this->assertCount(2, $john->accessibleProjects());
    }
}
