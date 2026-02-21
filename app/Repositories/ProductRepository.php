<?php

namespace App\Repositories;

use App\Models\Tenant\Product;

class ProductRepository extends BaseRepository
{
    function __construct(Product $product)
    {
        $this->setInstance($product);
    }

    function saveImage($product, $image)
    {
        if ($product && $image) {
            $product->image()->delete();
            $product->image()->create([
                'path' => $image,
                'key' => 'image'
            ]);
        }
    }

    function saveGallery($product, $gallery = [])
    {
        if ($product && count($gallery) > 0) {
            foreach ($gallery as $image) {
                $product->gallery()->create([
                    'path' => $image,
                    'key' => 'gallery'
                ]);
            }
        }
    }

    function removeImage($product)
    {
        if ($product) {
            $product->image()->delete();
        }
    }


    function removeGalleryImage($imageId)
    {
        $image = $this->instance->gallery()->where('id', $imageId)->first();
        if ($image) {
            $image->delete();
        }
    }

    function search($term) {
        $query = $this->instance->newQuery();

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('code', 'like', '%' . $term . '%')
                    ->orWhere('sku', 'like', '%' . $term . '%');
            });
        }

        return $query->first();
    }
}
