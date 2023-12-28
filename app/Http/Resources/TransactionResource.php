<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'transaction_number' => $this->transaction_number,
            'attributes' => [
                'transaction_number' => $this->transaction_number,
                'user_id' => $this->user_id,
                'qty' => $this->qty,
                'total' => $this->total,
                'created_at' => date('d-m-Y', strtotime($this->created_at))
            ],
            'relationships' => [
                'product' => [
                    'id' => (string) $this->product->id,
                    'attributes' => [
                        'name' => $this->product->name,
                        'price_sell' => $this->product->price_sell
                    ]
                ]
            ]
        ];
    }
}
