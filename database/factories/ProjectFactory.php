<?php

use App\Project;
use App\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title'       => $faker->sentence,
        'description' => $faker->paragraph,
        'notes'       => 'Foo Bar Notes',
        'user_id'     => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
