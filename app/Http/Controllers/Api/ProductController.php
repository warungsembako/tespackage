<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/product",
     *     tags={"Product"},
     *     description="Endpoint get product",
     *     summary="Service product",
     *     security={{"sanctum": {}}},
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index()
    {
        $data =  ProductResource::collection(
            Product::all()
        );

        if (sizeof($data) < 1) {
            return $this->error('', 'Data not found', 404);
        }

        return $this->success($data, 'Data found', 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/api/product",
     *     tags={"Product"},
     *     description="Endpoint add new product",
     *     summary="Service product",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="unit_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="desc",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price_buy",
     *                          type="float"
     *                      ),
     *                      @OA\Property(
     *                          property="price_sell",
     *                          type="float"
     *                      ),
     *                      @OA\Property(
     *                          property="qty",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "unit_id":1,
     *                     "category_id":1,
     *                     "name":"Beras",
     *                     "desc":"Beras Pulen",
     *                     "price_buy":15000,
     *                     "price_sell":18000,
     *                     "qty":180,
     * 
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Field product must be filled")
     * )
     */
    public function store(ProductRequest $request)
    {
        $request->validated();

        $product = Product::create([
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'desc' => $request->desc,
            'price_buy' => $request->price_buy,
            'price_sell' => $request->price_sell,
            'qty' => $request->qty,
        ]);

        return $this->success(new ProductResource($product), 'Created successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/product/{id}",
     *     tags={"Product"},
     *     description="Endpoint get product specified id",
     *     summary="Service product",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function show(Product $product)
    {
        return $this->success(new ProductResource($product), 'Data found', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="/api/product/{id}",
     *     tags={"Product"},
     *     description="Endpoint add new product",
     *     summary="Service product",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="product data id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="unit_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="desc",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="price_buy",
     *                          type="float"
     *                      ),
     *                      @OA\Property(
     *                          property="price_sell",
     *                          type="float"
     *                      ),
     *                      @OA\Property(
     *                          property="qty",
     *                          type="ineteger"
     *                      )
     *                 ),
     *                 example={
     *                     "unit_id":1,
     *                     "category_id":1,
     *                     "name":"Beras",
     *                     "desc":"Beras Pulen",
     *                     "price_buy":15000,
     *                     "price_sell":18000,
     *                     "qty":180,
     * 
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Field product must be filled")
     * )
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $request->validated();

        $product->update($request->all());

        return $this->success(new ProductResource($product), 'Updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *      path="/api/product/{id}",
     *      operationId="delete product",
     *      tags={"Product"},
     *      summary="Delete existing product",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation"
     *       )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, 204);
    }
}
