## 介绍

symfony/lock for webman

在 webman 中简化使用业务锁功能

解决以下问题：

 - 并发业务操作有时候需要锁来防止并发导致的数据插入或更新问题
 - 单独使用 symfony/lock 时一般使用 $factory->createLock('key')，此时 key 是一个字符串，不利于后期维护或多处使用



## 安装

`compoer require ledc/locker`



## 使用

### 定义一个自己的 Locker 类

比如：`support\facade\Locker.php`，继承 `Ledc\Locker`

然后在类上方加入注释（用于代码提示），举例如下：

```php
<?php

namespace support\facade;

use Symfony\Component\Lock\LockInterface;

/**
 * @method static LockInterface order(?string $orderId = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null)
 * @method static LockInterface changeCash(?string $userId = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null)
 */
class Locker extends \Ledc\Locker
{
}
```

### 业务中使用

```php
<?php

namespace app\controller;

use support\facade\Locker;

class User {
    public function changeCash()
    {
        $lock = Locker::changeCash($currentUserId, 5, true);
        if (!$lock->acquire()) {
            throw new \Exception('操作太频繁，请稍后再试');
        }
        try {
            // 修改用户金额
        } finally {
            $lock->release();
        }

        return 'ok';
    }
}
```
