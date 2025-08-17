<?php

use App\Enums\LeadDisqualificationReason;
use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource;
use App\Models\Account;
use App\Models\Lead;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

beforeEach(function() {
    login();

    Account::factory(25)->create();
});

it('can see', function() {
    $response = $this->get(LeadResource::getUrl('index'));

    $response->assertStatus(200);

    // title
    $response->assertSeeText('Leads');

    // widgets
    $response->assertSeeText([
        'Open Leads', 'Qualified leads',
        'Disqualified leads', 'Avg Estimated Revenue'
    ]);
    
    // tabs
    $response->assertSeeText([
        'All', 'Prospect', 'Open', 'Qualified',
        'Disqualified'
    ]);

    // button
    $response->assertSeeText('New lead');
});

it('can list', function () {
    $lead = Lead::factory(10)->create();
 
    livewire(LeadResource\Pages\ListLeads::class)
        ->assertCanSeeTableRecords($lead);
});

it('can render create page', function () {
    $this->get(LeadResource::getUrl('create'))->assertSuccessful();
});

it('can create', function () {
    $newData = Lead::factory()->make([
        'status' => LeadStatus::Prospect->value
    ]);
 
    livewire(LeadResource\Pages\CreateLead::class)
        ->fillForm([
            'title' => $newData->title,
            'customer_id' => $newData->customer_id,
            'source' => $newData->source,
            'estimated_revenue' => $newData->estimated_revenue,
            'description' => [
                'type' => 'doc',
                'content' => [
                    ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $newData->description]]]
                ],
            ],
            'status' => $newData->status,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
 
    $this->assertDatabaseHas(Lead::class, [
        'title' => $newData->title,
        'customer_id' => $newData->customer_id,
        'source' => $newData->source,
        'estimated_revenue' => (string)$newData->estimated_revenue,
        'description' => '<p>'. $newData->description .'</p>',
        'status' => $newData->status,
    ]);
});

it('can validate input', function () {
    $newData = Lead::factory()->make();
 
    livewire(LeadResource\Pages\CreateLead::class)
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
    $this->get(LeadResource::getUrl('edit', [
        'record' => Lead::factory()->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $lead = Lead::factory()->create([
        'status' => LeadStatus::Prospect->value
    ]);
 
    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->assertFormSet([
            'title' => $lead->title,
            'customer_id' => $lead->customer_id,
            'status' => $lead->status->value,
            'source' => $lead->source,
            'description' => '<p>' . $lead->description . '</p>',
            'estimated_revenue' => $lead->estimated_revenue,
        ]);
});

it('can save', function () {
    $lead = Lead::factory()->create([
        'status' => LeadStatus::Prospect->value
    ]);
    $newData = Lead::factory()->make();
 
    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'customer_id' => $newData->customer_id,
            'source' => $newData->source,
            'description' => [
                'type' => 'doc',
                'content' => [
                    ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $newData->description]]]
                ],
            ],
            'estimated_revenue' => $newData->estimated_revenue,
            'status' => LeadStatus::Open->value
        ])
        ->call('save')
        ->assertHasNoFormErrors();
 
    expect($lead->refresh())
        ->title->toBe($newData->title)
        ->customer_id->toBe($newData->customer_id)
        ->source->toBe($newData->source)
        ->description->toBe('<p>' . $newData->description . '</p>')
        ->estimated_revenue->toBe($newData->estimated_revenue)
        ->status->value->toBe(LeadStatus::Open->value);
});

it('can validate input on edit form', function () {
    $lead = Lead::factory()->create();
    $newData = Lead::factory()->make();
 
    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->fillForm([
            'title' => null,
            'customer_id' => null,
            'status' => null
        ])
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'required',
            'customer_id' => 'required',
            'status' => 'required'
        ]);
});

it('can delete', function () {
    $lead = Lead::factory()->create([
        'status' => LeadStatus::Open->value // Open
    ]);
 
    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);
 
    $this->assertModelMissing($lead);
});

it('can qualify', function() {
    $lead = Lead::factory()->create([
        'status' => LeadStatus::Prospect->value // Prospect
    ]);

    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->callAction('qualify');
  
    expect($lead->refresh())
        ->status->value->toBe(LeadStatus::Qualified->value);

    // TODO: test for redirect

    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->assertActionHidden('qualify')
        ->assertActionHidden('disqualify')
        ->assertActionHidden(DeleteAction::class)
        ->assertActionVisible('open-deal');
});

it('can disqualify', function() {
    $lead = Lead::factory()->create([
        'status' => LeadStatus::Prospect->value // Prospect
    ]);

    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->callAction('disqualify', data: [
            'disqualification_reason' => LeadDisqualificationReason::Bad_Data->value,
            'disqualification_description' => 'Spam form submission'
        ]);

    expect($lead->refresh())
        ->status->value->toBe(LeadStatus::Disqualified->value)
        ->disqualification_reason->toBe(LeadDisqualificationReason::Bad_Data->value)
        ->disqualification_description->toBe('Spam form submission');

    livewire(LeadResource\Pages\EditLead::class, [
        'record' => $lead->getRouteKey(),
    ])
        ->assertActionHidden('qualify')
        ->assertActionHidden('disqualify')
        ->assertActionHidden(DeleteAction::class);
});
