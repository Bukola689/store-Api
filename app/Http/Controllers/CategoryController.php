<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(5);

        if($categories->isEmpty()) {
            throw new NotFoundHttpException('Category Not Found');
        }

        return response()->json($categories);
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
            'name' => 'required|string|min:3|max:15|unique:stores,name',
            'details' => 'required|string|min:10|max:225'       
        ]);

        if($validator->fails()) {
            return response()->json(['Validator Error'], 400);
        }

        try {
            $category = Category::create($request->all());
        } catch (HttpException $th) {
            throw $th;
        }

        $response = [
            'id' => $category->id,
            'message' => 'Category Created Successfully'
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if(! $category) {
            throw new NotFoundHttpException('Category Does Not Exist');
        }

        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if($request->name) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:15|unique:stores,name'
            ]);
           
            if($validator->fails()) {
                return response()->json('name is empty or has been used', 400);
            };

            $category->name = $request->name;
        }

        if($request->details) {
            $validator = Validator::make($request->all(), [
                'details' => 'required|string|max:225'
            ]);

            if($validator->fails()) {
                return response()->json('details is empty', 400);
            };

            $category->details = $request->details;
        }

        if($category->isDirty()) {
            $category->save();

            return response()->json([
                'id' => $category->id,
                'message' => 'Category updated successfully'
            ]);
        }

        return response()->json(['Nothing to Update '], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        
        if(! $category) {
            throw new NotFoundHttpException('category does not exist');
         }
 
         try {
             $category->delete();

         } catch (HttpException $th) {
             throw $th;
         }

         $response = [
            'id' => $category->id,
            'message' => 'Category delete Successfully'
        ];

        return response()->json($response, 200);
    }
}
