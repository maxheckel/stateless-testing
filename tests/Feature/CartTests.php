<?php

namespace Tests\Feature;

use App\Cart;
use App\CartItem;
use App\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartTests extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function it_adds_an_item_to_an_existing_cart()
    {
        $user = factory(User::class)->create();
        $cart = factory(Cart::class)->create([
            'user_id'=>$user->id
        ]);
        $product = factory(Product::class)->create();

        $this->actingAs($user)->json('POST', '/api/cart', [
            'product_id'=>$product->id
        ])->assertStatus(200);

        $cartItemCheck = CartItem::where([
            'cart_id'=>$cart->id,
            'product_id'=>$product->id
        ]);
        $this->assertNotNull($cartItemCheck);
    }

    public function it_adds_an_item_to_a_new_cart(){
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();

        $this->actingAs($user)->json('POST', '/api/cart', [
            'product_id'=>$product->id
        ])->assertStatus(200);

        $cartCheck = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cartCheck);
    }

    public function it_adds_a_duplicate_item_to_a_cart(){
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();
        $cart = factory(Cart::class)->create([
            'user_id'=>$user->id
        ]);
        factory(CartItem::class)->create([
            'cart_id'=>$cart->id,
            'product_id'=>$product->id,
            'quantity'=>1
        ]);

        $this->actingAs($user)->json('POST', '/api/cart', [
            'product_id'=>$product->id
        ])->assertStatus(200);

        $cartCheck = CartItem::where([
            'cart_id'=>$cart->id,
            'product_id'=>$product->id
        ])->first();
        $this->assertEquals(2, $cartCheck->quantity);
    }
}
