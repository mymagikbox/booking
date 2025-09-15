<?php
namespace common\widgets;

use \Yii;
use kartik\alert\AlertInterface;
use yii\bootstrap5\Alert as BaseAlert;
use yii\bootstrap5\Html;

/**
 * Extends the \yii\bootstrap\Alert widget with additional styling and auto fade out options.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Alert extends BaseAlert
{
    /**
     * @var bool auto fill data
     */
    public $autoFill = false;

    public string $icon = '';
    public string $type = '';
    public int $delay = 5000;

    /**
     * Init widget
     */
    public function init()
    {
        if(Yii::$app->getResponse()->getStatusCode() != 302) {
            $types = [AlertInterface::TYPE_INFO, AlertInterface::TYPE_DANGER, AlertInterface::TYPE_SUCCESS, AlertInterface::TYPE_WARNING, AlertInterface::TYPE_PRIMARY, AlertInterface::TYPE_DEFAULT, AlertInterface::TYPE_CUSTOM];
            if($this->autoFill) {
                $find = false;
                foreach($types as $type) {
                    if (Yii::$app->session->hasFlash($type)) {
                        $this->type = $type;
                        $this->body = Yii::$app->session->getFlash($type);
                        $find = true;
                        break;
                    }
                }
                if(!$find) {
                    $this->type = AlertInterface::TYPE_INFO;
                }
            }
            if(!empty($this->body)) {
                Html::addCssClass($this->options, ['class' => $this->type]);

                parent::init();
            }
        }
    }

    /**
     * Runs the widget
     */
    public function run()
    {
        if(!empty($this->body)) {
            return parent::run();
        }
    }
}
