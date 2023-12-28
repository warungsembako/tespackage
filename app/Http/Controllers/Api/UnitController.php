<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Traits\HttpResponses;

class UnitController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/unit",
     *     tags={"Unit"},
     *     description="Endpoint get unit",
     *     summary="Service unit",
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
     *     @OA\Response(response="200", description="Data found"),
     * )
     */
    public function index()
    {
        $data =  UnitResource::collection(
            Unit::all()
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
     *     path="/api/unit",
     *     tags={"Unit"},
     *     description="Endpoint add new unit",
     *     summary="Service unit",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="unit",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="desc",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "unit":"kg",
     *                     "desc":"Kilogram"
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="409", description="Unit already exists")
     * )
     */
    public function store(UnitRequest $request)
    {
        // Cek apakah unit sudah ada dalam database
        $existingUnit = Unit::where('unit', $request->unit)->first();

        if ($existingUnit) {
            return $this->error('', 'Unit already exists', 409); // 409 Conflict
        }

        $unit = Unit::create([
            'unit' => $request->unit,
            'desc' => $request->desc,
        ]);

        return $this->success(new UnitResource($unit), 'Created successfully', 201); // 201 Created
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/unit/{id}",
     *     tags={"Unit"},
     *     description="Endpoint get unit specified id",
     *     summary="Service unit",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Unit id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Data found"),
     * )
     */
    public function show(Unit $unit)
    {
        return $this->success(new UnitResource($unit), 'Data found', 200);
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
     *      path="/api/unit/{id}",
     *      operationId="update unit",
     *      tags={"Unit"},
     *      summary="Update existing unit",
     *      description="Returns updated unit data",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="unit data id",
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
     *                          property="unit",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="desc",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "unit":"kg",
     *                     "desc":"Kilogram"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Updated successfully"
     *       )
     * )
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $request->validated();

        $unit->update($request->all());

        return $this->success(new UnitResource($unit), 'Updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *      path="/api/unit/{id}",
     *      operationId="delete unit",
     *      tags={"Unit"},
     *      summary="Delete existing unit",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="unit id",
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
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response(null, 204);
    }
}
