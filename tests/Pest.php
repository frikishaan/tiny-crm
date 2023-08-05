<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

/**
 * Function to login user
 */
function login()
{
    actingAs(User::find(1));
}