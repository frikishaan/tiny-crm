<?php

it('has a login page', function () {
    $this->get('/app/login')->assertStatus(200);
});

test('unauthenticated user cannot access dashboard', function() {
    $this->get('/app')
        ->assertStatus(302)
        ->assertRedirect('/app/login');
});

test('authenticated user can access the dashboard', function () {

    login();

    $response = $this->get('/app');
    
    $response->assertStatus(200);

    $response->assertSeeText(['Dashboard', 'Open Leads']);
});

it('can see widgets', function() {
    login();
    
    $response = $this->get('/app');
    
    $response->assertStatus(200);

    $response->assertSeeText([
        'Dashboard', 'Open Leads', 'Qualified leads', 'Disqualified leads', 
        'Avg Estimated Revenue', 'Open deals', 'Deals won', 
        'Avg Revenue (per deal)', 'Total revenue'
    ]);
});

it('can see the charts', function() {
    login();

    $response = $this->get('/app');
    
    $response->assertStatus(200);

    $response->assertSeeText([
        'Deals won per month', 'Revenue per month'
    ]);
});