<?php

use App\Filament\Resources\AccountResource;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use Filament\Pages\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function() {
    login();
});

it('can see', function() {
    $response = $this->get(AccountResource::getUrl('index'));

    $response->assertStatus(200);

    $response->assertSeeText('Accounts');

    $response->assertSeeText('New account');
});

it('can list', function () {
    $accounts = Account::factory(10)->create();
 
    livewire(AccountResource\Pages\ListAccounts::class)
        ->assertCanSeeTableRecords($accounts);
});

it('can render create page', function () {
    $this->get(AccountResource::getUrl('create'))->assertSuccessful();
});

it('can create', function () {
    $newData = Account::factory()->make();
 
    livewire(AccountResource\Pages\CreateAccount::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'phone' => $newData->phone,
            'address' => $newData->address,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Account::class, [
        'name' => $newData->name,
        'email' => $newData->email,
        'phone' => $newData->phone,
        'address' => $newData->address,
    ]);
});

it('can validate input', function () {
    $newData = Account::factory()->make();
 
    livewire(AccountResource\Pages\CreateAccount::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('can render edit page', function () {
    $this->get(AccountResource::getUrl('edit', [
        'record' => Account::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $account = Account::factory()->create();
 
    livewire(AccountResource\Pages\EditAccount::class, [
        'record' => $account->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $account->name,
            'email' => $account->email,
            'phone' => $account->phone,
            'address' => $account->address,
        ]);
});

it('can save', function () {
    $account = Account::factory()->create();
    $newData = Account::factory()->make();
 
    livewire(AccountResource\Pages\EditAccount::class, [
        'record' => $account->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'phone' => $newData->phone,
            'address' => $newData->address,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($account->refresh())
        ->name->toBe($newData->name)
        ->email->toBe($newData->email)
        ->phone->toBe($newData->phone)
        ->address->toBe($newData->address);
});

it('can validate input on edit form', function () {
    $account = Account::factory()->create();
    $newData = Account::factory()->make();
 
    livewire(AccountResource\Pages\EditAccount::class, [
        'record' => $account->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('can delete', function () {
    $account = Account::factory()->create();
 
    livewire(AccountResource\Pages\EditAccount::class, [
        'record' => $account->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);
 
    $this->assertModelMissing($account);
});

it('can render contacts relation manager', function () {
    $account = Account::factory()
        ->has(Contact::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\ContactsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertSuccessful();
});

it('can render leads relation manager', function () {
    $account = Account::factory()
        ->has(Lead::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\LeadsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertSuccessful();
});

it('can render deals relation manager', function () {
    $account = Account::factory()
        ->has(Deal::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\DealsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertSuccessful();
});

it('can list related contacts', function () {
    $account = Account::factory()
        ->has(Contact::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\ContactsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertCanSeeTableRecords($account->contacts);
});

it('can list related leads', function () {
    $account = Account::factory()
        ->has(Lead::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\LeadsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertCanSeeTableRecords($account->leads);
});

it('can list related deals', function () {
    $account = Account::factory()
        ->has(Deal::factory()->count(10))
        ->create();
 
    livewire(AccountResource\RelationManagers\DealsRelationManager::class, [
        'ownerRecord' => $account,
    ])
        ->assertCanSeeTableRecords($account->deals);
});