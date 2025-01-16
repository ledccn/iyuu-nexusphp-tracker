<?php

namespace Ledc\Webman\Traits;

use InvalidArgumentException;

/**
 * 数据结构
 */
trait HasAttributes
{
    /**
     * 必填项
     * @var array<string>
     */
    protected array $requiredKeys = [];
    /**
     * 当前数据
     * @var array
     */
    protected array $attributes = [];

    /**
     * 构造函数
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->checkMissingKeys();
    }

    /**
     * @return array
     */
    final public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * 输出Json数据
     * @return string
     */
    final public function toJson(): string
    {
        $json = json_encode($this->attributes, JSON_UNESCAPED_UNICODE);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }
        return $json;
    }

    /**
     * 判断是否存在指定KEY
     * @param array|int|string|null $key
     * @return bool
     */
    final public function has(array|int|string|null $key): bool
    {
        if (empty($this->attributes)) {
            return false;
        }

        if (is_null($key)) {
            return false;
        }

        $keys = (array)$key;
        if ([] === $keys) {
            return false;
        }

        foreach ($keys as $k) {
            $subKeyArray = $this->attributes;
            if (self::exists($this->attributes, $k)) {
                continue;
            }

            foreach (explode('.', (string)$key) as $segment) {
                if (static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 检查必填项
     * @throws InvalidArgumentException
     */
    public function checkMissingKeys(): bool
    {
        if (empty($this->requiredKeys)) {
            return true;
        }

        $missingKeys = [];
        foreach ($this->requiredKeys as $key) {
            if (!$this->has($key)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            throw new InvalidArgumentException(sprintf('"%s" cannot be empty.' . "\r\n", implode(',', $missingKeys)));
        }

        return true;
    }

    /**
     * 获取配置项参数
     * - 支持 . 分割符
     * @param int|string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    final public function get(int|string $key = null, mixed $default = null): mixed
    {
        if (null === $key) {
            return $this->attributes;
        }
        $keys = explode('.', $key);
        $value = $this->attributes;
        foreach ($keys as $index) {
            if (!static::exists($value, $index)) {
                return $default;
            }
            $value = $value[$index];
        }
        return $value;
    }

    /**
     * 设置 $this->attributes
     * @param int|string|null $key
     * @param mixed $value
     * @return static
     */
    final public function set(int|string|null $key, mixed $value): static
    {
        if ($key === null) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$key] = $value;
        }
        return $this;
    }

    /**
     * 判断是否存在
     * @param array<int|string, mixed> $array
     * @param int|string $key
     * @return bool
     */
    final public static function exists(array $array, int|string $key): bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * 转字符串
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * 当对不可访问属性调用 isset() 或 empty() 时，__isset() 会被调用
     * @param int|string $name
     * @return bool
     */
    public function __isset(int|string $name): bool
    {
        return self::exists($this->attributes, $name);
    }

    /**
     * 当对不可访问属性调用 unset() 时，__unset() 会被调用
     * @param int|string $name
     */
    public function __unset(int|string $name)
    {
        unset($this->attributes[$name]);
    }

    /**
     * 当访问不可访问属性时调用
     * @param int|string $name
     * @return array|string|null
     */
    public function __get(int|string $name)
    {
        return $this->get($name);
    }

    /**
     * 在给不可访问（protected 或 private）或不存在的属性赋值时，__set() 会被调用。
     * @param int|string $key
     * @param mixed $value
     */
    public function __set(int|string $key, mixed $value)
    {
        $this->set($key, $value);
    }
}
