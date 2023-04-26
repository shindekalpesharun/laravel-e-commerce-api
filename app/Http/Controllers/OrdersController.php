<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\OrderItems;
use Validator;

use App\Models\Orders;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve all Orders from the database
        $orders = Orders::where([['user_id', $request->user()->id]])->get();

        // Return a JSON response with the tasks data
        return response()->json(['data' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules for the task data
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|boolean',
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // TODO: cart to order item data move 
        $carts_items = Carts::where('user_id', $request->user()->id)->get()->toArray();
        if (sizeof($carts_items) == 0) {
            return response()->json(['error' => "Cart was Empty"], 400);
        }
        $total_price = 0;
        $order_item_array = [];
        foreach ($carts_items as $index => $item) {
            unset($item['id'], $item['user_id']);
            $item['price'] = Product::where('id', $item['product_id'])->get()[0]->price;
            $total_price += $item['price'] * $item['quantity'];
            $order_item_array[] = $item;
            Carts::find($carts_items[$index]['id'])->delete();
        }

        // TODO: create order colume with total price
        $data = $request->all();
        $data['total_price'] = $total_price;
        $data['user_id'] = $request->user()->id;
        $orders = Orders::create($data);
        foreach ($order_item_array as &$data) {
            $data['order_id'] = $orders->id;
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
        };
        OrderItems::insert($order_item_array);
        return response()->json(['data' => $orders], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
