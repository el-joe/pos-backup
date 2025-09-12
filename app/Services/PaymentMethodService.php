<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodService
{
    public function __construct(private PaymentMethodRepository $repo) {}

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


    function find($id, $relations = [], $filter = [])
    {
        return $this->repo->find($id, $relations, $filter);
    }

    function delete($id)
    {
        return $this->repo->delete($id);
    }

    function save($id = null,$data) {
        if($id) {
            $paymentMethod = $this->repo->find($id);
            if($paymentMethod) {
                $paymentMethod->update($data);
                return $paymentMethod;
            }
        }

        return $this->repo->create($data);
    }

    function defaultPaymentMethod() {
        $default = $this->repo->first([],[
            'active' => 1,
            'slug' => 'cash'
        ]);

        if(!$default) {
            $default = $this->repo->first([],[
                'active' => 1
            ]);
        }

        if(!$default) {
            $default = $this->repo->first();
        }

        return $default;
    }
}
