<?php

use App\Project;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body'       => $faker->sentence,
        'completed'  => true,
        'project_id' => function () {
            return factory(Project::class)->create()->id;
        }
    ];
});