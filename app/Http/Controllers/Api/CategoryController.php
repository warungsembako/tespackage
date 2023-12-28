<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    /**
     * @OA\Get(
     *     path="/api/category",
     *     tags={"Category"},
     *     description="Endpoint get category",
     *     summary="Service category",
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
        $data =  CategoryResource::collection(
            Category::all()
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
     *     path="/api/category",
     *     tags={"Category"},
     *     description="Endpoint add new category",
     *     summary="Service category",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="category",
     *                          type="string"
     *                      ),
     *                  ),
     *                 example={
     *                     "category":"sembako"
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Field category must be filled")
     * )
     */

    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'category' => $request->category
        ]);

        return $this->success(new CategoryResource($category), 'Created successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/category/{id}",
     *     tags={"Category"},
     *     description="Endpoint get category specified id",
     *     summary="Service category",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function show(Category $category)
    {
        return $this->success(new CategoryResource($category), 'Data found', 200);
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
     *      path="/api/category/{id}",
     *      operationId="update category",
     *      tags={"Category"},
     *      summary="Update existing category",
     *      description="Returns updated category data",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="category data id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="category",
     *                          type="string"
     *                      ),
     *                  ),
     *                 example={
     *                     "category":"sembako"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated successfully"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $request->validated();

        $category->update($request->all());

        return $this->success(new CategoryResource($category), 'Updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *      path="/api/category/{id}",
     *      operationId="delete category",
     *      tags={"Category"},
     *      summary="Delete existing category",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
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
    public function destroy(Category $category)
    {
        $category->delete();

        return response(null, 204);
    }
}
