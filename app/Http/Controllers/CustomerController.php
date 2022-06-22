<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
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
        //
        $customer = Customer::get();
        return $customer;
    }
    public function pagination($page)
    {
        $customer = Customer::paginate($page);
        return $customer;
    }
    public function search($keyword)
    {
        $customer = Customer::where('name','like',"%".$keyword."%")->paginate();
        return $customer;
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
        //
        //Validate data
        $data = $request->only('name', 'no_hp', 'email');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'no_hp' => 'required',
            'email' => 'required|email'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new order
        $customer = Customer::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email
        ]);

        //order created, return success response
        return response()->json([
            'success' => true,
            'message' => 'order created successfully',
            'data' => $customer
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
        $customer = Customer::find($id);
    
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Customer not found.'
            ], 400);
        }
    
        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
        //Validate data
        $data = $request->only('name', 'no_hp', 'email');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'no_hp' => 'required',
            'email' => 'required|email',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update order
        $customer = $customer->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email
        ]);

        //order updated, return success response
        if($customer){
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer
            ], Response::HTTP_OK);            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Customer cant Update',
                'data' => $customer
            ], Response::HTTP_OK);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'customer deleted successfully'
        ], Response::HTTP_OK);
    }
}
