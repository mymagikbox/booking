<?php

namespace common\core\model;

use yii\base\Model;

class BaseModel extends Model
{
    public function getFirstErrorModel(): ?string
    {
        foreach ($this->getFirstErrors() as $attribute => $error) {
            return $error;
        }

        return null;
    }
}