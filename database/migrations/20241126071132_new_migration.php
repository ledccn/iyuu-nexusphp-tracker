<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table\Column;
use Phinx\Migration\AbstractMigration;

/**
 * 变更wa_users表
 */
final class NewMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('wa_users');
        $table->addColumn((new Column())
            ->setName('user_type')
            ->setType(Column::INTEGER)
            ->setLimit(MysqlAdapter::INT_TINY)
            ->setSigned(false)
            ->setComment('用户类型')
            ->setDefault(0))
            ->update();
    }
}
