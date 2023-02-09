<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Create Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        // Create Account & Contacts
        Account::factory(25)->create()->each(function ($account){
            Contact::factory(rand(1, 10))->create([
                'account_id' => $account->id
            ]);
        });

        // Create Leads
        Lead::factory(100)->create()->each(function ($lead){
            if($lead->status == 3){ // Qualified
                // Create Deal for this lead
                Deal::create([
                    'title' => $lead->title,
                    'customer_id' => $lead->customer_id,
                    'lead_id' => $lead->id,
                    'estimated_revenue' => $lead->estimated_revenue,
                    'status' => 1
                ]);
            }
        });

        // Create Products
        Product::factory(25)->create();
    }
}
