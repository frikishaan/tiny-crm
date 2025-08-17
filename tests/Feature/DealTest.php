<?php

use App\Enums\DealStatus;
use App\Filament\Resources\DealResource;
use App\Filament\Resources\DealResource\Pages\EditDeal;
use App\Models\Account;
use App\Models\Deal;
use App\Models\DealProduct;
use App\Models\Lead;
use App\Models\Product;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function() {
    login();

    Account::factory(25)->create();
});

it('can see', function() {
    $response = $this->get(DealResource::getUrl('index'));

    $response->assertStatus(200);

    // Title
    $response->assertSeeText('Deals');
    
    // widgets
    $response->assertSeeText([
        'Open deals', 'Deals won', 'Avg Revenue (per deal)', 'Total revenue'
    ]);
    
    // button
    $response->assertSeeText('New deal');

    // tabs
    $response->assertSeeText(['All', 'Open', 'Won', 'Lost']);
});

it('can list', function () {
    $deals = Deal::factory(10)->create();
 
    livewire(DealResource\Pages\ListDeals::class)
        ->assertCanSeeTableRecords($deals);
});

it('can render create page', function () {
    $this->get(DealResource::getUrl('create'))->assertSuccessful();
});

it('can create', function () {
    $newData = Deal::factory()->make([
        'status' => DealStatus::Open->value
    ]);
 
    livewire(DealResource\Pages\CreateDeal::class)
        ->fillForm([
            'title' => $newData->title,
            'customer_id' => $newData->customer_id,
            'estimated_revenue' => $newData->estimated_revenue,
            'actual_revenue' => $newData->actual_revenue,
            'status' => $newData->status,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Deal::class, [
        'title' => $newData->title,
        'customer_id' => $newData->customer_id,
        'estimated_revenue' => (string)$newData->estimated_revenue,
        'actual_revenue' => $newData->actual_revenue,
        'status' => $newData->status,
    ]);
});

it('can validate input', function () {
    $newData = Deal::factory()->make();
 
    livewire(DealResource\Pages\CreateDeal::class)
        ->fillForm([
            'title' => null,
            'customer_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'customer_id' => 'required'
        ]);
});

it('can render edit page', function () {
    $this->get(DealResource::getUrl('edit', [
        'record' => Deal::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $deal = Deal::factory()->create([
        'status' => DealStatus::Open->value
    ]);
 
    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->assertFormSet([
            'title' => $deal->title,
            'customer_id' => $deal->customer_id,
            'status' => $deal->status->value,
            'actual_revenue' => $deal->actual_revenue,
            'estimated_revenue' => $deal->estimated_revenue,
        ]);
});

it('can save', function () {
    $deal = Deal::factory()->create([
        'status' => DealStatus::Open->value
    ]);

    $newData = Deal::factory()->make();
 
    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'customer_id' => $newData->customer_id,
            'actual_revenue' => $newData->actual_revenue,
            'estimated_revenue' => $newData->estimated_revenue,
            'status' => DealStatus::Open->value
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($deal->refresh())
        ->title->toBe($newData->title)
        ->customer_id->toBe($newData->customer_id)
        ->source->toBe($newData->source)
        ->actual_revenue->toBe($newData->actual_revenue)
        ->estimated_revenue->toBe($newData->estimated_revenue)
        ->status->value->toBe(DealStatus::Open->value);
});

it('can validate input on edit form', function () {
    $deal = Deal::factory()->create();
    $newData = Deal::factory()->make();
 
    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->fillForm([
            'title' => null,
            'customer_id' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'required',
            'customer_id' => 'required',
        ]);
});

it('can delete', function () {
    $deal = Deal::factory()->create([
        'status' => DealStatus::Open->value // Open
    ]);
 
    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);
 
    $this->assertModelMissing($deal);
});

it('can render product relations', function () {
    Product::factory(10)->create();
    
    $deal = Deal::factory()
        ->has(DealProduct::factory()->count(3), 'products')
        ->create();
 
    livewire(DealResource\RelationManagers\ProductsRelationManager::class, [
        'ownerRecord' => $deal,
        'pageClass' => EditDeal::class
    ])
        ->assertSuccessful();
});

it('can close as won', function() {
    Product::factory(10)->create();
    
    $deal = Deal::factory()
        ->has(DealProduct::factory()->count(3), 'products')
        ->create([
            'status' => DealStatus::Open->value
        ]);

    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->callAction('close_as_won');

    expect($deal->refresh())
        ->status->value->toBe(DealStatus::Won->value);

    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->assertActionHidden('close_as_won')
        ->assertActionHidden('close_as_lost');
});

it('can close as lost', function() {
    Product::factory(10)->create();
    
    $deal = Deal::factory()
        ->has(DealProduct::factory()->count(3), 'products')
        ->create([
            'status' => DealStatus::Open->value
        ]);

    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->callAction('close_as_lost');

    expect($deal->refresh())
        ->status->value->toBe(DealStatus::Lost->value);

    livewire(DealResource\Pages\EditDeal::class, [
        'record' => $deal->getRouteKey(),
    ])
        ->assertActionHidden('close_as_won')
        ->assertActionHidden('close_as_lost');
});
