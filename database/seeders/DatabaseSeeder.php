<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\DealProduct;
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
        
        // Create Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tinycrm.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);

        // Create additional users
        \App\Models\User::factory(10)->create();

        // Create Account & Contacts
        Account::factory(25)->create()->each(function ($account){
            Contact::factory(rand(1, 10))->create([
                'account_id' => $account->id
            ]);
        });

        // Create Products
        // Product::factory(25)->create();
        $this->create_products();

        // Create Leads
        Lead::factory(100)->create()->each(function ($lead){
            if($lead->status == 3){ // Qualified
                // Create Deal for this lead
                $deal = Deal::create([
                    'title' => $lead->title,
                    'customer_id' => $lead->customer_id,
                    'lead_id' => $lead->id,
                    'estimated_revenue' => $lead->estimated_revenue,
                    'status' => rand(1, 3),
                    'description' => $lead->description,
                    'created_at' => $lead->created_at
                ]);

                // Add products to deal
                DealProduct::factory(rand(1, 5))->create([
                    'deal_id' => $deal->id
                ]);
            }
        });
    }

    private function create_products()
    {
        Product::insert([
            [
                'name' => 'Intel i7 Processors',
                'product_id' => 'PRO-JU89JKEW',
                'type' => 2,
                'price' => 1000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AMD Ryzen 5 5500',
                'product_id' => 'PRO-89JDFEW',
                'type' => 2,
                'price' => 800,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Web Development',
                'product_id' => 'PRO-OYT71NM',
                'type' => 1,
                'price' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'UI Design',
                'product_id' => 'PRO-HAU6HJAS',
                'type' => 1,
                'price' => 60,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Corsair RGB RAM - 8 GB',
                'product_id' => 'PRO-5ODAWSNZ',
                'type' => 2,
                'price' => 29,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Consultancy',
                'product_id' => 'PRO-SWA8127J',
                'type' => 1,
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dedicated Developer (8 hours/day)',
                'product_id' => 'PRO-OUY13B9S',
                'type' => 1,
                'price' => 80,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dell Keyboards',
                'product_id' => 'PRO-EJM71JUI',
                'type' => 2,
                'price' => 49,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nvidia GeForce - 1060 Ti - 8GB',
                'product_id' => 'PRO-NV812DIQ',
                'type' => 2,
                'price' => 1200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Wordpress Development',
                'product_id' => 'PRO-W412MNAK',
                'type' => 1,
                'price' => 45,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
