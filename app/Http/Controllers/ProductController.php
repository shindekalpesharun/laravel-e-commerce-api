<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Product;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all Product from the database
        $products = Product::all();

        // Return a JSON response with the tasks data
        return response()->json(['data' => $products], Response::HTTP_OK);
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'categories_id' => 'required|nullable|integer',
            'price' => 'required|integer',
            'image_url' => 'required|file|mimes:jpeg,png',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $image_path = $request->file('image_url')->store('image', 'public');

        // Create a new task object with the validated data
        $data = $request->all();
        $data['image_url'] = $image_path;
        $product = Product::create($data);

        // Return a success response with the created task data
        return response()->json(['data' => $product], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the Product from the database by ID
        $product = Product::find($id);

        // If the task doesn't exist, return an error response
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Return a JSON response with the task data
        return response()->json(['data' => $product]);
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
