<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users.
     * 
     * @return Collection
     */
    public function getAllUsers(): Collection;

    /**
     * Get user by ID.
     * 
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User;

    /**
     * Find user by credentials.
     * 
     * @param array $credentials
     * @return User|null
     */
    public function findByCredentials(array $credentials): ?User;

    /**
     * Update user profile.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateProfile(int $id, array $data): bool;
}
