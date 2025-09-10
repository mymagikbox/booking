<?php

class m130524_201442_init extends Migration
{
    protected function getTable(): string
    {
        return '{{%user}}';
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'verification_token' => $this->string()->defaultValue(null),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}
