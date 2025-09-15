<?php

namespace common\CQS\Domain\Entity;

use yii\db\ActiveRecord;

/***
 * @property int $id
 * @property int $author_id
 * @property int $phone
 *
 * @property Author $author
 **/
class AuthorSubscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author_subscription}}';
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}