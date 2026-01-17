<?php

// Autoload Composer dependencies
require 'vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve the secret key from the environment variables
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];

try{
  $stripe = new \Stripe\StripeClient($stripeSecretKey);

   // You can now use the $stripe object to interact with the Stripe API
    // For example, to list products:
    // $products = $stripe->products->all(['limit' => 3]);
    // print_r($products);

    echo "Stripe client created successfully!";

} catch(\Exception $e){
  echo "Error creating Stripe client: " . $e->getMessage();
}

$checkout_session = $stripe->checkout->sessions->create([
  // 'line_items' => [[
  //   'price_data' => [
  //     'currency' => 'usd',
  //     'product_data' => [
  //       'name' => 'T-shirt',
  //     ],
  //     'unit_amount' => 2000,
  //   ],
  //   'quantity' => 1,
  // ]],

  'mode' => 'subscription',
  'payment_method_types' => ['card'],
  'line_items' => [[
    // 'price' => 'price_1SkB5S0lJ7p6e5R3Mp50ahU2', // existing price ID
    'price' => 'price_1SmcYz0lJ7p6e5R39KjKc6ZU',
    'quantity' => 1,
  ]],
  'metadata' => [
    'order_id' => 'ORDER_101'
  ],
  'success_url' => 'http://localhost/stripe-api/success.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

?>