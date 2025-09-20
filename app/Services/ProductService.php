<?php

namespace App\Services;

use App\Models\Tenant\Tax;
use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(private ProductRepository $repo) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function activeList($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter + [
            'active' => 1
        ], $perPage, $orderByDesc);
    }


    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function search($term)
    {
        return $this->repo->search($term);
    }

    function create($data) {
        return $this->repo->create($data);
    }

    function save($id = null,$data) {
        if(isset($data['tax_id'])){
            $tax = Tax::find($data['tax_id']);
            if($tax){
                $data['tax_rate'] = $tax->rate;
            }else{
                $data['tax_rate'] = 0;
            }
        }

        if($id) {
            $product = $this->find($id);
            if($product) {
                $product->update($data);
            }
        }else{
            $product = $this->create($data);
        }


        // save image
        if(isset($data['image'])) {
            $this->repo->saveImage($product, $data['image']);
        }

        // save gallery
        if(isset($data['gallery'])) {
            $this->repo->saveGallery($product, $data['gallery']);
        }

        return $product;
    }

    function removeImage($productId) {
        $product = $this->repo->find($productId);
        if($product) {
            $this->repo->removeImage($product);
            return $product;
        }

        return null;
    }

    function removeGalleryImageByPath($imagePath) {
        $imagePath = str_replace(url('/storage'),'',$imagePath);
        $image = $this->repo->first([],[
            'path' => $imagePath
        ]);

        if($image) {
            $this->repo->removeGalleryImage($image);
            return $image;
        }

        return null;
    }

    function delete($id) {
        $product = $this->repo->find($id);
        if($product) {
            return $product->delete();
        }

        return false;
    }
}
