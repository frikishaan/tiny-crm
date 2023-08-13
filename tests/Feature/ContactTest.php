<?php

use App\Filament\Resources\ContactResource;
use App\Models\Account;
use App\Models\Contact;
use Filament\Pages\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function() {
    login();
});

it('can see', function() {
    $response = $this->get(ContactResource::getUrl('index'));

    $response->assertStatus(200);

    $response->assertSeeText('Contacts');

    $response->assertSeeText('New contact');
});

it('can list', function () {
    $contacts = Contact::factory(10)->create();
 
    livewire(ContactResource\Pages\ListContacts::class)
        ->assertCanSeeTableRecords($contacts);
});

it('can render create page', function () {
    $this->get(ContactResource::getUrl('create'))->assertSuccessful();
});

it('can create', function () {
    $account = Account::factory()->create();
    
    $newData = Contact::factory()->make();
 
    livewire(ContactResource\Pages\CreateContact::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'phone' => $newData->phone,
            'account_id' => $account->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Contact::class, [
        'name' => $newData->name,
        'email' => $newData->email,
        'phone' => $newData->phone,
        'account_id' => $account->id,
    ]);
});

it('can validate input', function () {
    $newData = Contact::factory()->make();
 
    livewire(ContactResource\Pages\CreateContact::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('can render edit page', function () {
    $this->get(ContactResource::getUrl('edit', [
        'record' => Contact::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $account = Account::factory()->create();
    $contact = Contact::factory()->create([
        'account_id' => $account->id
    ]);
 
    livewire(ContactResource\Pages\EditContact::class, [
        'record' => $contact->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'account_id' => $contact->account_id,
        ]);
});

it('can update', function () {
    $account = Account::factory()->create();
    $newAccount = Account::factory()->create();
    $contact = Contact::factory()->create([
        'account_id' => $account->id
    ]);
    $newData = Contact::factory()->make();
 
    livewire(ContactResource\Pages\EditContact::class, [
        'record' => $contact->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
            'phone' => $newData->phone,
            'account_id' => $newAccount->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($contact->refresh())
        ->name->toBe($newData->name)
        ->email->toBe($newData->email)
        ->phone->toBe($newData->phone)
        ->account_id->toBe($newAccount->id);
});

it('can validate input on edit form', function () {
    $account = Account::factory()->create();
    $contact = Contact::factory()->create([
        'account_id' => $account->id
    ]);
    $newData = Contact::factory()->make();
 
    livewire(ContactResource\Pages\EditContact::class, [
        'record' => $contact->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('can delete', function () {
    $contact = Contact::factory()->create([
        'account_id' => Account::factory()->create()->id
    ]);
 
    livewire(ContactResource\Pages\EditContact::class, [
        'record' => $contact->getRouteKey(),
    ])
        ->callPageAction(DeleteAction::class);
 
    $this->assertModelMissing($contact);
});