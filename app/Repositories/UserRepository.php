<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     * 
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get all users.
     * 
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get user by ID.
     * 
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Find user by credentials.
     * 
     * @param array $credentials
     * @return User|null
     */
    public function findByCredentials(array $credentials): ?User
    {
        $query = $this->model->newQuery();

        foreach ($credentials as $key => $value) {
            if ($key !== 'password') {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Update user profile.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateProfile(int $id, array $data): bool
    {
        $user = $this->getUserById($id);

        if (!$user) {
            return false;
        }

        // If there's a password change, hash it
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $user->update($data);
    }
}
