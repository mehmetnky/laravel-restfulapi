<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Order;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return $this->sendResponse(OrderResource::collection($orders), 'Orders Fetched.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'orderCode' => 'required',
            'productId' => 'required|integer',
            'quantity' => 'required|integer',
            'address' => 'required',
            'shippingDate' => 'required|date_format:Y-m-d|after_or_equal:now',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $order = Order::create($input);
        return $this->sendResponse(new OrderResource($order), 'Order created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  String $orderCode orderCode of the order
     * @return \Illuminate\Http\Response
     */
    public function show(String $orderCode)
    {
        $order = Order::where('orderCode', $orderCode)->first();

        if (empty($order)) {
            return $this->sendError('Couldn\'t find an order with orderCode: '.$orderCode);
        }

        return $this->sendResponse(new OrderResource($order), 'Order Fetched.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String $orderCode orderCode of the order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $orderCode)
    {
        $order = Order::where('orderCode', $orderCode)->first();

        if (empty($order)) {
            return $this->sendError('Couldn\'t find an order with orderCode: '.$orderCode);
        }

        $dateNow = time();
        $isUserAdmin = Auth::user()->hasRole('admin');
        
        $isOrderDateOlderThanNow = strtotime($order->shippingDate) < $dateNow ? TRUE : FALSE;
        if ($isOrderDateOlderThanNow) {
            if (!$isUserAdmin) {
                return $this->sendError('You can\'t change an old order\'s shipping date!');
            }   
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'productId' => 'integer',
            'quantity' => 'integer',
            'shippingDate' => 'date_format:Y-m-d',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        // This logic is going to check if the new shippingDate is older than now
        // and prevent all the updating operation if user is not admin.
        // Validation rule 'after_or_equal:now' would have prevent this whole mess
        // but there could always be a falsy/wrong shipment and admin can want it to be gone.
        $isNewDateOlderThanNow = FALSE;
        if (!empty($input['shippingDate'])) {
            $newDate = strtotime($input['shippingDate']);

            $isNewDateOlderThanNow = $newDate < $dateNow ? TRUE : FALSE;
        }

        if ($isNewDateOlderThanNow) {
            if ($isUserAdmin) {
                $order->shippingDate = $input['shippingDate'];
            } else {
                return $this->sendError('You can\'t change an order\'s shipping to an older date!');
            }
        } else {
            $order->shippingDate = $input['shippingDate'];
        }

        $order->orderCode = !empty($input['orderCode']) ? $input['orderCode'] : $order->orderCode;
        $order->productId = !empty($input['productId']) ? $input['productId'] : $order->productId;
        $order->quantity = !empty($input['quantity']) ? $input['quantity'] : $order->quantity;
        $order->address = !empty($input['address']) ? $input['address'] : $order->address;

        if ($order->save()) {
            return $this->sendResponse(new OrderResource($order), 'Order updated.');
        }

        return $this->sendError('An unexpected error occured!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String $orderCode orderCode of the order
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $orderCode)
    {
        $order = Order::where('orderCode', $orderCode)->first();

        if (empty($order)) {
            return $this->sendError('Couldn\'t find an order with orderCode: '.$orderCode);
        }

        if ($order->delete()) {
            return $this->sendResponse([], 'Order Deleted.');
        }

        return $this->sendError('An unexpected error occured!');
    }
}
