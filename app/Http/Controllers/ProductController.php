<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource by its Id.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll(Request $request)
    {
        $categoryId = $request->query('categoryId');

        $products = Product::with('categories')->whereHas('categories', function($query) use($categoryId) {
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        })->get();

        if ($products->isEmpty()) {
            throw new NotFoundHttpException('Category Not Found For Product');
        }

        return response()->json($products);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeId, $productLineId)
    {
        $products = Product::whereHas('productlines.brands.stores', function($query) use($storeId, $productLineId) {
            $query->where('store_id', $storeId);
            $query->where('product_line_id', $productLineId);
        })->get();

        if ($products->isEmpty()) {
            throw new NotFoundHttpException('Product Not Found');
        }

        return response()->json($products);
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
    public function store(Request $request, $storeId, $productLineId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:5|max:15|unique:brands,name',
            'caption' => 'required|string|min:3|max:60',
            'details' => 'required|string|min:3',
            'categoryId' => 'required|int'
           ]);
    
           if($validator->fails()) {
              return response()->json(['Validation Error'] , 400);
           }

           $productLine = ProductLine::whereHas('brands.stores', function($query) use($storeId) {
              $query->where('store_id', $storeId);
           })->find($productLineId);

           if(!$category = Category::find($request->categoryId)) {
              throw new NotFoundHttpException('Category does not exist');
           }

           if (! $productLine) {
            throw new NotFoundHttpException('Product Line does not exist for brand');
           }

           try {
            $product = $productLine->products()->create($request->all());
            $product->categories()->attach($category->id);

           } catch (HttpException $th) {
             throw $th;
           }

           $response = [
             'id' => $product->id,
             'message' => 'Product Saved Successfully'
           ];

           return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($storeId, $productLineId, $id)
    {
        $product = Product::with('categories')->whereHas('productlines.brands.stores', function($query) use($storeId, $productLineId) {
            $query->where('store_id', $storeId);
            $query->where('product_line_id', $productLineId);
        })->find($id);

        if (! $product) {
            throw new NotFoundHttpException('Product Not Found');
        }

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $storeId, $productLineId, $id)
    {
        $product = Product::whereHas('productlines.brands.stores', function($query) use($storeId, $productLineId) {
            $query->where('store_id', $storeId);
            $query->where('product_line_id', $productLineId);
        })->find($id);

        if($request->categoryId && ! $category = Category::find($request->categoryId)) {
            throw new NotFoundHttpException('Category Does Not Exist');
         }

         if (! $product) {
          throw new NotFoundHttpException('Product Not Found');
         }

         if($request->title) {
            $validator = Validator::make($request->all(), [
               'title' => 'required|string|min:3|max:15|unique:brands,name'
        ]);

        if($validator->fails()) {
            return response()->json(['please fill in a unique title'], 400);          }
       
        $product->title = $request->title;

       }

        if($request->caption) {
            $validator = Validator::make($request->all(), [
               'caption' => 'required|string|min:3|max:15|unique:brands,name'
        ]);

        if($validator->fails()) {
            return response()->json(['please fill in a unique caption'], 400);          
        }

        $product->caption = $request->caption;

        }

        if($request->details) {
            $validator = Validator::make($request->all(), [
               'details' => 'required|string|min:3|max:15|unique:brands,name'
        ]);

        if($validator->fails()) {
            return response()->json(['please fill in details'], 400);         
        }

        $product->details = $request->details;

        }

        if($request->categoryId) {
            $validator = Validator::make($request->all(), [
               'title' => 'required|int'
        ]);

        

        if($validator->fails()) {
            return response()->json(['please fill in a unique title'], 400);         
        }

        $product->categories()->sync($category->id);

    }

        if($product->isDirty()) {
            try {
              $product->save();
            } catch (HttpException $th) {
                throw $th;
            }

        }

            $response = [
                'id' => $product->id,
                'message' => 'Product Updated Successfullly'
            ];

            return response()->json($response, 200);
        
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeId, $productLineId, $id)
    {
        $product = Product::whereHas('productlines.brands.stores', function($query) use($storeId, $productLineId) {
            $query->where('store_id', $storeId);
            $query->where('product_line_id', $productLineId);
        })->find($id);

         if (! $product) {
          throw new NotFoundHttpException('Product Does Not Exist OnProduct Line');
         }

         try {
            $product->delete();
         } catch (HttpException $th) {
            throw $th;
         }

         $response = [
            'id' => $product->id,
            'message' => 'Product Deleted Successfully'
         ];

         return response()->json($response, 200);

    }
}
