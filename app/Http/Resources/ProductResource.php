<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'is_public' => $this->is_public,

            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            'product_type' => new ProductTypeResource(
                $this->whenLoaded('productType')
            ),

            'brand' => new BrandResource(
                $this->whenLoaded('brand')
            ),

            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),
        ];
    }
}
