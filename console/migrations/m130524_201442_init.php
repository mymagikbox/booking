<?php
use console\migrations\Migration;

class m130524_201442_init extends Migration
{
    private string $userName = 'admin';
    private string $userEmail = 'admin@mail.ru';
    private string $userPassword = '123456';

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
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_user_username', $this->getTable(), 'username', true);
        $this->createIndex('idx_user_auth_key', $this->getTable(), 'auth_key', true);

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert($this->getTable(), [
                'username'          => $this->userName,
                'email'             => $this->userEmail,
                'auth_key'          => Yii::$app->security->generateRandomString(),
                'password_hash'     => Yii::$app->security->generatePasswordHash($this->userPassword),
                'created_by'        => 1,
                'updated_by'        => 1,
                'created_at'        => time(),
                'updated_at'        => time(),
            ])->execute();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}
