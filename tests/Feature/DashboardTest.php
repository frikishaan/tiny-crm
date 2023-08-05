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

// it('can see contacts', function() {
//     login();

//     $response = $this->get('/app/contacts');

//     $response->assertStatus(200);

//     $response->assertSeeText('Contacts');
// });

// it('can see leads', function() {
//     login();

//     $response = $this->get('/app/leads');

//     $response->assertStatus(200);

//     $response->assertSeeText('Leads');
// });

// it('can see deals', function() {
//     login();

//     $response = $this->get('/app/deals');

//     $response->assertStatus(200);

//     $response->assertSeeText('Deals');
// });

// it('can see products', function() {
//     login();

//     $response = $this->get('/app/products');

//     $response->assertStatus(200);

//     $response->assertSeeText('Products');
// });

// it('can see users', function() {
//     login();

//     $response = $this->get('/app/users');

//     $response->assertStatus(200);

//     $response->assertSeeText('Users');
// });
