<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ProductLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeId, $brandId)
    {
        $productLines = ProductLine::whereHas('brands.stores', function($query) use($storeId, $brandId) {
            $query->where('store_id', $storeId);
            $query->where('brand_id', $brandId);
        })->get();

        if($productLines->isEmpty()) {
            throw new NotFoundHttpException('ProductLine Does Not Exist For Brand');
        }

        return response()->json($productLines);
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
    public function store(Request $request, $storeId, $brandId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:15|unique:stores,name',
            'details' => 'required|string|min:10|max:225'       
        ]);

        if($validator->fails()) {
            return response()->json('Validator Fails');
        }

        $brand = Brand::whereHas('stores', function($query) use($storeId) {
            $query->where('store_id', $storeId);
        })->find($brandId);

        if(empty($brand)) {
            throw new NotFoundHttpException('ProductLine Does Not exist Exist For Brand');
        }

        $productline = $brand->productlines()->create([
            'name' => $request->name,
            'details' => $request->details
        ]);

        return response()->json([
            'id' => $productline->id,
            'message' => 'ProductLine Created Successfully'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function show($storeId, $brandId, $id)
    {
        $productLine = ProductLine::whereHas('brands.stores', function($query) use($storeId, $brandId) {
            $query->where('store_id', $storeId);
            $query->where('brand_id', $brandId);
        })->find($id);

        if(! $productLine) {
            throw new NotFoundHttpException('ProductLine Does Not exist Exist For Brand');
        }

        return response()->json($productLine);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductLine $productLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $storeId, $brandId, $id)
    {
        $productLine = ProductLine::whereHas('brands.stores', function($query) use($storeId, $brandId) {
            $query->where('store_id', $storeId);
            $query->where('brand_id', $brandId);
        })->find($id);

        if(! $productLine) {
            throw new NotFoundHttpException('ProductLine Does Not exist Exist For Brand');
        }

        if(! empty($request->name)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:15|unique:stores,name'
            ]);
           
            if($validator->fails()) {
                return response()->json('name is empty', 400);
            };

            $productLine->name = $request->name;
        }

        if(! empty($request->details)) {
            $validator = Validator::make($request->all(), [
                'details' => 'required|string|max:225'
            ]);

            if($validator->fails()) {
                return response()->json('details is empty', 400);
            };

            $productLine->details = $request->details;
        }

        if($productLine->isDirty()) {
            $productLine->save();

            return response()->json([
                'id' => $productLine->id,
                'message' => 'Product Line updated successfully'
            ]);
        }

        return response()->json(['Nothing To Update'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeId, $brandId, $id)
    {
        $productLine = ProductLine::whereHas('brands.stores', function($query) use($storeId, $brandId) {
            $query->where('store_id', $storeId);
            $query->where('brand_id', $brandId);
        })->find($id);

        if(! $productLine) {
            throw new NotFoundHttpException('ProductLine Does Not exist Exist For Brand');
        }

        try {
         $productLine = $productLine->delete();
        } catch (HttpException $th) {
            throw $th;
        }

        $response = [
            'id' => $productLine->id,
            'message' => 'Product Line updated successfully'
        ];

        return response()->json($response, 200);
    }
}
