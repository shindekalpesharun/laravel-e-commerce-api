<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Carts;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;


class CartsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve all Carts from the database
        $carts = Carts::where([['user_id', $request->user()->id]])->get();

        // Return a JSON response with the tasks data
        return response()->json(['data' => $carts]);
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
            'product_id' => 'required|integer|max:255',
            'quantity' => 'required|integer|max:255',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $carts = Carts::where([['user_id', $request->user()->id], ['product_id', $request->product_id]])->get();
        if (sizeof($carts) > 0) {
            $data = Carts::findOrFail($carts[0]->id);
            $data['quantity'] = $carts[0]->quantity + $request->quantity;
            $data->save();
            return response()->json(['data' => $data], 200);
        }

        // Create a new carts object with the validated data
        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        $carts = Carts::create($data);

        // Return a success response with the created task data
        return response()->json(['data' => $carts], Response::HTTP_CREATED);
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
