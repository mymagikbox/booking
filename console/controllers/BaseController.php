<?php

namespace console\controllers;

use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\VarDumper;

class BaseController extends Controller
{

    public $errorLogData;
    public $logData;

    /***
     * @param string $text
     * @param array $data
     */
    public function writeError($text, $data = [])
    {
        $this->stdout($text . PHP_EOL, Console::FG_RED);
        if(!empty($data)) {
            $this->stdout(VarDumper::dumpAsString($data, 10) . PHP_EOL, Console::FG_RED);
            $text .= "\n********* ERROR DATA *************\n" . VarDumper::dumpAsString($data, 10);
        }

        $error = date('Y-m-d H:i:s') . ' [-][error] ' . $text . PHP_EOL;
        $this->errorLogData .= $error;
        $this->logData .= $error;

        Yii::error($text);
    }

    /***
     * @param string $text
     * @param array $data
     */
    public function writeInfo($text, $data = [])
    {
        $this->stdout($text . PHP_EOL, Console::FG_CYAN);
        if(!empty($data)) {
            $this->stdout(VarDumper::dumpAsString($data, 10) . PHP_EOL, Console::FG_BLUE);
            $text .= "\n********* INFO DATA *************\n" . VarDumper::dumpAsString($data, 10);
        }

        $info = date('Y-m-d H:i:s') . ' [-][info] ' . $text . PHP_EOL;
        $this->logData .= $info;

        Yii::info($text);
    }

    /***
     * @param string $text
     * @param array $data
     */
    public function writeWarning($text, $data = [])
    {
        $this->stdout($text . PHP_EOL, Console::FG_YELLOW);
        if(!empty($data)) {
            $this->stdout(VarDumper::dumpAsString($data, 10) . PHP_EOL, Console::FG_YELLOW);
            $text .= "\n********* WARNING DATA *************\n" . VarDumper::dumpAsString($data, 10);
        }

        $warning = date('Y-m-d H:i:s') . ' [-][warning] ' . $text . PHP_EOL;
        $this->logData .= $warning;

        Yii::warning($text);
    }

    /***
     * @param string $text
     * @param array $data
     * @throws Exception
     */
    public function writeErrorWithException($text, $data = [])
    {
        $this->writeError($text, $data);
        throw new Exception($text);
    }
}