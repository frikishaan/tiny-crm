<?php

declare(strict_types=1);

namespace Pest\Livewire;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

/**
 * Compatibility shim for pest-plugin-livewire using Livewire v4 directly.
 * Provides the same function signature as pestphp/pest-plugin-livewire.
 *
 * @param  array<array-key, mixed>  $params
 * @return Testable<\Livewire\Component>
 */
function livewire(string $name, array $params = []): Testable
{
    return Livewire::test($name, $params);
}
