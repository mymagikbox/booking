<?php

use console\migrations\Migration;
use common\CQS\Domain\Entity\AuthorSubscription;

class m190124_110205_create_author_subscription extends Migration
{
    protected function getTable(): string
    {
        return AuthorSubscription::tableName();
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->bigInteger()->notNull(),
        ]);

        $this->createIndex('idx_author_subscription_phone', $this->getTable(), 'phone', true);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}
