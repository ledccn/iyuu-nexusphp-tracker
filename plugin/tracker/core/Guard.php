<?php

namespace plugin\tracker\core;

use plugin\tracker\app\model\User;

/**
 * 用户守卫
 */
interface Guard
{
    /**
     * 获取当前通过身份验证的用户.
     *
     * @return User|null
     */
    public function user(): ?User;

    /**
     * 获取当前通过身份验证的用户的ID
     *
     * @return int|string|null
     */
    public function id(): int|string|null;

    /**
     * 确定是否有用户实例
     *
     * @return bool
     */
    public function hasUser(): bool;

    /**
     * 设置当前用户实例.
     *
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): static;
}
