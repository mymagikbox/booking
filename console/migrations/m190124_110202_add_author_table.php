<?php


use common\CQS\Domain\Entity\Author;
use console\migrations\Migration;

class m190124_110202_add_author_table extends Migration
{
    protected function getTable(): string
    {
        return Author::tableName();
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_author_slug', $this->getTable(), 'slug', true);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}
