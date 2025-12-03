<?php

namespace App\Repositories;

use App\Interface\BaseInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseInterface {

    protected $instance;

    function setInstance(Model|Builder $model): void {
        $this->instance = $model;
    }

    function getInstance() {
        return $this->instance;
    }

    function list($relations = [],$filter = [],$perPage = null,$orderByDesc = null) {
        $model = $this->instance
            ->when(count($relations) > 0, fn($q)=>$q->with($relations))
            ->when(count($filter) > 0 , fn($q) => $q->filter($filter));

        if($orderByDesc != null){
            $model = $model->orderByDesc($orderByDesc);
        }

        if($perPage == null){
            $model = $model->get();
        }else{
            $model = $model->paginate($perPage)->withQueryString();
        }

        return $model;
    }

    function find(string|int|null $id,$relations = [],$filter = []) {
        return $this->instance
            // ->when(count($relations) > 0, fn($q) => $q->with($relations))
            ->when(count($filter) > 0, fn($q) => $q->filter($filter))
            ->find($id);
    }

    function first($relations = [],$filter = []) {
        return $this->instance->when(count($relations) > 0 ,fn($q) => $q->with($relations))
            ->when(count($filter) > 0 , fn($q) => $q->filter($filter))
            ->first();
    }

    function findOrFail(string|int $id,$relations = [],$filter = []) {
        return $this->instance->when(count($relations) > 0 ,fn($q) => $q->with($relations))
            ->when(count($filter) > 0 , fn($q) => $q->filter($filter))
            ->findOrFail($id);
    }

    function count($filter = []) {
        return $this->instance
            ->when(count($filter) > 0 , fn($q) => $q->filter($filter))
            ->count();
    }

    function create(array $data) {
        return $this->instance->create($data);
    }

    function update(string|int $id,array $data) {
        $target = $this->findOrFail($id);
        $target->update($data);

        return $target->refresh();
    }

    function delete(string|int $id) {
        return $this->findOrFail($id)->delete();
    }
}
