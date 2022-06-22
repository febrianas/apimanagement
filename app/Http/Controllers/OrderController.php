<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::get();
        return $order;
    }
    public function pagination($page)
    {
        $order = Order::paginate($page);
        return $order;
    }
    public function search($keyword)
    {
        $order = Order::where('name','like',"%".$keyword."%")->paginate();
        return $order;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'cust_id', 'price', 'quantity','update_by');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'cust_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'update_by' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new order
        $order = Order::create([
            'name' => $request->name,
            'cust_id' => $request->cust_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'update_by' => $request->update_by
        ]);

        //order created, return success response
        return response()->json([
            'success' => true,
            'message' => 'order created successfully',
            'data' => $order
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Orders::find($id);
    
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, order not found.'
            ], 400);
        }
    
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        //Validate data
        $data = $request->only('name', 'cust_id', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'cust_id' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update order
        $order = $order->update([
            'name' => $request->name,
            'cust_id' => $request->cust_id,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //order updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'order updated successfully',
            'data' => $order
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(order $order)
    {
        $order->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'order deleted successfully'
        ], Response::HTTP_OK);
    }
}