<?php
namespace fatjiong\daterangepicker;

// 胖纸囧
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

class DateRangePicker extends InputWidget
{
    public $selector;
    public $callback;
    public $options     = [];
    public $htmlOptions = [];
    public $moment      = true;

    public function init()
    {
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        if (!isset($this->htmlOptions['name'])) {
            $this->htmlOptions['name'] = $this->hasModel() ? Html::getInputName($this->model, $this->attribute) : $this->id;
        }

        if (!$this->name) {
            $this->name = $this->htmlOptions['name'];
        }

        parent::init();
    }

    public function run()
    {
        $this->registerPlugin();
    }

    protected function registerPlugin()
    {

        if ($this->moment) {
            DateRangePickerAsset::$extra_js[] = defined('YII_DEBUG') && YII_DEBUG ? 'moment.js' : 'moment.min.js';
        }

        if ($this->selector) {
            $this->registerJs($this->selector, $this->options, $this->callback);
        } else {
            $id = $this->htmlOptions['id'];
            echo Html::tag('input', '', $this->htmlOptions);
            $this->registerJs("#{$id}", $this->options, $this->callback);
        }
    }

    protected function registerJs($selector, $options, $callback)
    {
        $view = $this->getView();
        DateRangePickerAsset::register($view);
        $js   = [];
        $js[] = '$("' . $selector . '").daterangepicker(' . Json::encode($options) . ($callback ? ', ' . Json::encode($callback) : '') . ');';
        $view->registerJs(implode("\n", $js), View::POS_READY);

    }
}
