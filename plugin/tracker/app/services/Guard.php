<?php

namespace plugin\tracker\app\services;

use plugin\tracker\app\model\User;

/**
 * 用户守卫
 */
class Guard implements \plugin\tracker\core\Guard
{
    /**
     * 用户对象
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * @return User|null
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * @return int|string|null
     */
    public function id(): int|string|null
    {
        if (empty($this->user)) {
            return null;
        }
        return $this->user->id;
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->user instanceof User;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
