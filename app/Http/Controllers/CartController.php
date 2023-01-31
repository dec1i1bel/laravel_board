<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartPost;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function checkout()
    {
        return view('cart.checkout');
    }

    public function addPost(Request $request, $id)
    {
        $cartId = $request->cookie('cart_id');
        $quantity = $request->input('quantity') ?? 1;

        if (empty($cartId)) {
            $cart = Cart::create();
            $cartId = $cart->id;
        }
        $cart = Cart::findOrFail($cartId);
        $cartPosts = $cart->posts()->get();

        if (isset($cartPosts) && ($cartPosts->contains($id)) !== false) {
            /*если товар уже в корзине - обновляем количество*/
            $pivotQuantity = 0;
            foreach ($cartPosts as $post) {
                $pivotQuantity = $post->pivot->quantity;
            }
            $quantity = $pivotQuantity + $quantity;

            $pivotRow = CartPost::where('cart_id', '=', $cartId)
                                ->where('post_id', '=', $id);
            $pivotRow->update(['quantity' => $quantity]);
        } else {
            /*если товара нет - добавляем*/
            $cart->posts()->attach($id, ['quantity' => $quantity]);
        }

        return back()->withCookie(cookie('cart_id', $cartId, 60*24));
    }
}
