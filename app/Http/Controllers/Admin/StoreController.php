<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ! $user = auth()->user()) {
            throw new NotFoundHttpException('User Not Found');
        }

        $stores = Store::with('owner', 'users')
           ->where('owner_id', $user->id)
           ->get();

        return response()->json($stores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:15|unique:stores,name',
            'details' => 'required|string|min:10|max:225'       
        ]);

        if($validator->fails()) {
            return response()->json('Validator Fails');
        }

        $store = new Store;
        $store->owner_id = Auth::id();
        $store->name = $request->name;
        $store->details = $request->details;
        $store->save();

        return response()->json(['Store Saved Successsfully', $store->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( ! $user = auth()->user()) {
            throw new NotFoundHttpException('User Not Found');
        }

        $store = Store::with('users')->where('owner_id', $user->id)
           ->find($id);


        return response()->json($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreRequest  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        if( ! $user = auth()->user()) {
            throw new NotFoundHttpException('User Not Found');
        }

        $store = Store::where('owner_id', $user->id)
           ->find($id);

        if(! $store) {
            throw new NotFoundHttpException('Store does not exist');   
        }

        if(! empty($request->name)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:15|unique:stores,name'
            ]);
           
            if($validator->fails()) {
                return response()->json('name is empty', 400);
            };

            $store->name = $request->name;
        }

        if(! empty($request->details)) {
            $validator = Validator::make($request->all(), [
                'details' => 'required|string|max:225'
            ]);

            if($validator->fails()) {
                return response()->json('details is empty', 400);
            };

            $store->details = $request->details;
        }

        if($store->isDirty()) {
            $store->save();

            return response()->json([
                'id' => $store->id,
                'message' => 'Store updated successfully'
            ]);
        }

        return response()->json(['Nothing to Update '], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if( ! $user = auth()->user()) {
            throw new NotFoundHttpException('User Not Found');
        }

        $store = Store::where('owner_id', $user->id)
           ->find($id);

        if(! $store) {
           throw new NotFoundHttpException('store does not exist');
        }

        try {
            $store->delete();

            return response()->json([
                'id' => $store->id,
                'message' => 'Store delete Successfully'
            ]);
        } catch (HttpException $th) {
            throw $th;
        }
          
    }
}
