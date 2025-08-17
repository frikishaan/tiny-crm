<?php

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function() {
    login();
});

it('can see', function() {
    $response = $this->get(ProductResource::getUrl('index'));

    $response->assertStatus(200);

    $response->assertSeeText('Products');

    $response->assertSeeText('New product');
});

it('can list', function () {
    $product = Product::factory(10)->create();
 
    livewire(ProductResource\Pages\ListProducts::class)
        ->assertCanSeeTableRecords($product);
});

it('can render create page', function () {
    $this->get(ProductResource::getUrl('create'))->assertSuccessful();
});

it('can create', function () {
    $newData = Product::factory()->make();
 
    livewire(ProductResource\Pages\CreateProduct::class)
        ->fillForm([
            'product_id' => $newData->product_id,
            'name' => $newData->name,
            'price' => $newData->price,
            'type' => $newData->type,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Product::class, [
        'product_id' => $newData->product_id,
        'name' => $newData->name,
        'price' => $newData->price,
        'type' => $newData->type,
    ]);
});

it('can validate input', function () {

    Product::factory()->create([
        'product_id' => 'PRO-12345'
    ]);
 
    livewire(ProductResource\Pages\CreateProduct::class)
        ->fillForm([
            'name' => null,
            'product_id' => 'PRO-12345',
            'price' => null,
            'type' => null
        ])
        ->call('create')
        ->assertHasFormErrors([
            'product_id' => 'unique',
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
        ]);
});

it('can render edit page', function () {
    $this->get(ProductResource::getUrl('edit', [
        'record' => Product::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $product = Product::factory()->create();
 
    livewire(ProductResource\Pages\EditProduct::class, [
        'record' => $product->getRouteKey(),
    ])
        ->assertFormSet([
            'product_id' => $product->product_id,
            'name' => $product->name,
            'price' => $product->price,
            'type' => $product->type->value,
        ]);
});

it('can update', function () {
    $product = Product::factory()->create();
    $newData = Product::factory()->make();
 
    livewire(ProductResource\Pages\EditProduct::class, [
        'record' => $product->getRouteKey(),
    ])
        ->fillForm([
            'product_id' => $newData->product_id,
            'name' => $newData->name,
            'price' => $newData->price,
            'type' => $newData->type,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($product->refresh())
        ->name->toBe($newData->name)
        ->product_id->toBe($newData->product_id)
        ->price->toBe($newData->price)
        ->type->toBe($newData->type);
});

it('can validate input on edit form', function () {
    $product = Product::factory()->create();

    Product::factory()->create([
        'product_id' => 'PRO-54321'
    ]);
 
    livewire(ProductResource\Pages\EditProduct::class, [
        'record' => $product->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
            'product_id' => 'PRO-54321',
            'price' => null,
            'type' => null
        ])
        ->call('save')
        ->assertHasFormErrors([
            'product_id' => 'unique',
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
        ]);
});

it('can delete', function () {
    $product = Product::factory()->create();
 
    livewire(ProductResource\Pages\EditProduct::class, [
        'record' => $product->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);
 
    $this->assertModelMissing($product);
});
