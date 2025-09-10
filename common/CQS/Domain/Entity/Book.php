<?php

namespace app\CQS\Domain\Entity;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/***
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string $description
 * @property string $isbn Международный стандартный книжный номер
 * @property string $cover_image
 */
class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

}