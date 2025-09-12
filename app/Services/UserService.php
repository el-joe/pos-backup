<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(private UserRepository $repo,private AccountService $accountService) {}

    function list($relations = [], $filter = [], $perPage = null, $orderByDesc = null)
    {
        return $this->repo->list($relations, $filter, $perPage, $orderByDesc);
    }

    function find($id = null, $relations = [])
    {
        return $this->repo->find($id, $relations);
    }

    function findOrFail($id = null, $relations = [])
    {
        return $this->repo->findOrFail($id, $relations);
    }


    function save($id = null,$data) {
        if($id) {
            $user = $this->repo->find($id);
            if($user) {
                $user->update($data);
            }
        }else{
            $user = $this->repo->create($data);
        }

        if($user && !$id) {
            $this->accountService->createAccountForUser($user);
        }

        return $user;
    }

    function delete($id) {
        $user = $this->repo->find($id);
        if($user) {
            return $user->delete();
        }

        return false;
    }

    function checkIfUserExistsIntoSameType(string $data,string $type) {
        $userEmail = $this->repo->first(filter : ['email' => $data, 'type' => $type , 'is_deleted' => false]);
        $userPhone = $this->repo->first(filter : ['phone' => $data , 'type' => $type , 'is_deleted' => false]);
        return $userEmail !== null || $userPhone !== null;
    }
}
