<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/transaction",
     *     tags={"Transaction"},
     *     description="Endpoint get transaction",
     *     summary="Service Transaction",
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
        // dd(Transaction::all());
        $data =  TransactionResource::collection(
            Transaction::all()
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
     *     path="/api/transaction",
     *     tags={"Transaction"},
     *     description="Endpoint post transaction",
     *     summary="Service Transaction",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="product_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="qyt",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="total",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "product_id":1,
     *                     "qty":1,
     *                     "total":400000,
     * 
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Field product must be filled")
     * )
     */
    public function store(TransactionRequest $request)
    {
        $request->validated();
        $product = Product::where('id', intval($request->product_id))->first();
        $now = $product->qty - $request->qty;
        $phone_number = Auth::user()->no_telp;
        // dd($phone_number);

        if ($now < 0) {
            return $this->error('', 'Jumlah yang anda masukan melebihi stock', 404);
        }

        $product = $product->first();
        $transaction = Transaction::create([
            'transaction_number' => 'TR-' . time(),
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'total' => $request->total
        ]);
        $qty_now = $product->qty - $request->qty;

        DB::table('products')->where('id', $request->product_id)->update(
            [
                'qty' => $now,
            ]
        );

        $msg = array(
            'to' => $phone_number,
            'isgroup' => false,
            'messages' => "*[✅Selamat ".Auth::user()->name." berhasil Transaction]*\n\nNama Barang: ".$transaction->product->name."\nTR Number: ".$transaction->transaction_number."\nPrice : Rp. ".$transaction->total."\nQTY: ".$transaction->qty."\n\nSegera lengkapi pembayaran🙏",
        );
        // JSON encode the data.
        $jsonData = json_encode($msg);
        // The URL to send the POST request to.
        $url= 'https://api.wa.my.id/api/send/message/text';
      
        $ch = curl_init($url);
        // Set cURL options.
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'Content-Type: application/json',
            'token: ' . env('TOKEN_WHATSAUTH')
        ));
        
        // Execute the POST request.
        $response = curl_exec($ch);
        // Check for cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        
        // Close cURL session.
        curl_close($ch);
        // If there was an error, handle it here.
        if (isset($error_msg)) {
            // Log or echo the error message.
            echo "cURL Error: " . $error_msg;
            return response()->json([
                "status"=> "error",
                "message"=> "cURL Error: " .$error_msg
            ],500);
        }
        
        return $this->success(new TransactionResource($transaction), 'Created successfully ' . $response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/transaction/{transaction_number}",
     *     tags={"Transaction"},
     *     description="Endpoint get transaction specified id",
     *     summary="Service transaction",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="transaction_number",
     *         in="path",
     *         description="transaction transaction_number",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function show($transaction_number)
    {
        $transaction = Transaction::where('transaction_number', $transaction_number)->firstOrFail();
        return $this->success(new TransactionResource($transaction), 'Data found', 200);
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
     *     path="/api/transaction/{transaction_number}",
     *     tags={"Transaction"},
     *     description="Endpoint add new transaction",
     *     summary="Service transaction",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="transaction_number",
     *          description="category data transaction_number",
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
     *                          property="product_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="qyt",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="total",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "product_id":1,
     *                     "qty":1,
     *                     "totol":400000,
     * 
     *                }
     *             )
     *         )
     *      ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Field product must be filled")
     * )
     */
    public function update(TransactionRequest $request, $transaction_number)
    {
        $transaction = Transaction::where('transaction_number', $transaction_number)->firstOrFail();
        $validatedData = $request->validated();
        $transaction->update($validatedData);
        return $this->success(new TransactionResource($transaction), 'Updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Delete(
     *      path="/api/transactiondelete/{transaction_number}",
     *      operationId="delete transaction",
     *      tags={"Transaction"},
     *      summary="Delete existing transaction",
     *      description="Deletes a record and returns no content",
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="transaction_number",
     *          description="product transaction_number",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation"
     *       )
     * )
     */
    public function destroy($transaction_number)
    {
        $transaction = Transaction::where('transaction_number', $transaction_number)->delete();
        return response(null, 204);
    }
}
