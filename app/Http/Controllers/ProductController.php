<?php

namespace App\Http\Controllers;

use App\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * ProductController constructor.
     */
    public function __construct()
    {

    }


    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Product::all(), 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $product = Product::create(
            [
                'name' => $request->name,
                'description' => $request->description,
                'units' => $request->units,
                'price' => $request->price,
                'image' => $request->image
            ]
        );

        return response()->json(
            [
                'status' => (bool)$product,
                'data' => $product,
                'message' => $product ? 'Product Created!' : 'Error Creating Product'
            ]
        );
    }


    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product, 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $name = time() . "_" . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $name);
        }
        return response()->json(asset("images/$name"), 201);
    }


    /**
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $status = $product->update(
            $request->only(['name', 'description', 'units', 'price', 'image'])
        );

        return response()->json(
            [
                'status' => $status,
                'message' => $status ? 'Product Updated!' : 'Error Updating Product'
            ]
        );
    }


    /**
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function updateUnits(Request $request, Product $product): JsonResponse
    {
        $product->increment('units', $request->get('units'));

        $status = $product->save();

        return response()->json(
            [
                'status' => $status,
                'message' => $status ? 'Units Added!' : 'Error Adding Product Units'
            ]
        );
    }


    /**
     * @param Product $product
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Product $product): JsonResponse
    {
        $status = $product->delete();

        return response()->json(
            [
                'status' => $status,
                'message' => $status ? 'Product Deleted!' : 'Error Deleting Product'
            ]
        );
    }


    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function getOrders(Product $product): JsonResponse
    {


        return response()->json(
            [
                'status' => 200,
                'data' => $product->orders
            ]
        );
    }
}
