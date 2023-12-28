<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                'desc' => $this->desc,
                'price_buy' => $this->price_buy,
                'price_sell' => $this->price_sell,
                'qty' => $this->qty,
            ],
            'relationships' => [
                'unit' => [
                    'id' => (string) $this->unit->id,
                    'attributes' => [
                        'unit' => $this->unit->unit,
                        'desc' => $this->unit->desc
                    ]
                ],
                'category' => [
                    'id' => (string) $this->category->id,
                    'attributes' => [
                        'category' => $this->category->category,
                    ]
                ]
            ]
        ];
    }
}
