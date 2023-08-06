<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeId)
    {
        $brands = Brand::whereHas('stores', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->get();

        if (empty($brands)) {
            throw new NotFoundHttpException('store does not exist');
        }

        return response()->json($brands);
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
       $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:5|max:15|unique:brands,name',
        'details' => 'required|string|min:3|max:225'
       ]);

       if($validator->fails()) {
          return response()->json('Validator Error');
       }

       try {
         $brand = Brand::create($request->all());
       } catch (HttpException $th) {
          throw $th;
       }

       return response()->json([
        'id' => $brand->id,
        'message' => 'Brand Created Successully'
       ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($storeId, $id)
    {
        $brand = Brand::whereHas('stores', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->find($id);

        if(empty($brand)) {
            throw new NotFoundHttpException('Store Id Not Found');
        }

        return response()->json($brand);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $storeId, $id)
    {
        $brand = Brand::whereHas('stores', function($query) use($storeId) {
            $query->where('store_id', $storeId);
        })->find($id);

        if(!$brand) {
            throw new NotFoundHttpException('Brand Not Found');
        }

        if(!empty($request->name)) {
            $validator = Validator::make($request->all(), [
               'name' => 'required|string|min:3|max:15|unique:brands,name'
        ]);

        if($validator->fails()) {
            return response()->json(['please fill in a unique name'], 400);          }
        }

        $brand->name = $request->name;

        if(!empty($request->details)) {
            $validator = Validator::make($request->all(), [
               'details' => 'required|string|min:3|max:225'
        ]);

        if($validator->fails()) {
            return response()->json(['Fill your details'], 400);          }
        }

        $brand->details = $request->details;

        try {
            if($brand->isDirty()) {
                $brand->save();

                return response()->json([
                    'id' => $brand->id,
                    'message' => 'Brand Updated Successfully'
                ]);

            }
             return response()->json([
                'message' => 'Nothing To Update'
             ]);

        } catch (HttpException $th) {
            throw $th;
        }    
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeId, $id)
    {
        $brand = Brand::whereHas('stores', function($query) use($storeId) {
            $query->where('store_id', $storeId);
        })->find($id);

        if(!$brand) {
            throw new NotFoundHttpException('Brand Not Found');
        }  

        try {
            $brand->delete();

        } catch (HttpException $th) {
            throw $th;
        }  
        
        return response()->json([
            'id' => $brand->id,
            'message' => 'Brand Deleted Successfully'
        ]);
    }
}
