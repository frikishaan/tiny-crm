<?php

test('Not debugging statements are left in our code.')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('All files are enums')
    ->expect('App\Enums')
    ->toBeEnums();
