<?php

namespace App\Http\Controllers;

use App\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Order::with(['product'])->get(), 200);
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function deliverOrder(Order $order): JsonResponse
    {
        $order->is_delivered = true;
        $status = $order->save();

        return response()->json(
            [
                'status' => $status,
                'data' => $order,
                'message' => $status ? 'Order Delivered!' : 'Error Delivering Order'
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $order = Order::create(
            [
                'product_id' => $request->product,
                'user_id' => Auth::id(),
                'quantity' => $request->quantity,
                'address' => $request->address
            ]
        );

        return response()->json(
            [
                'status' => (bool)$order,
                'data' => $order,
                'message' => $order ? 'Order Created!' : 'Error Creating Order'
            ]
        );
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json($order, 200);
    }


    /**
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $status = $order->update(
            $request->only(['quantity'])
        );

        return response()->json(
            [
                'status' => $status,
                'message' => $status ? 'Order Updated!' : 'Error Updating Order'
            ]
        );
    }

    /**
     * @param Order $order
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Order $order): JsonResponse
    {
        $status = $order->delete();

        return response()->json(
            [
                'status' => $status,
                'message' => $status ? 'Order Deleted!' : 'Error Deleting Order'
            ]
        );
    }
}
