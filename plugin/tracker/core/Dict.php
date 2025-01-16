<?php

namespace plugin\tracker\core;

use Ledc\Webman\Traits\HasAttributes;

/**
 * 响应字典
 */
class Dict
{
    use HasAttributes;

    /**
     * 判断是否为空
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->attributes);
    }
}
