<?php

namespace dmstr\bootstrap;

use Yii;
use \yii\bootstrap\Tabs as BaseTabs;
use yii\web\View;

/**
 * Tabs widget which remembers the tab status
*/
class Tabs extends BaseTabs
{

    public function init()
    {
        parent::init();
        static::registerAssets($this->view,$this->id);
    }

    /**
     * @param View $view
     * @param string $widget_id
    */
    public static function registerAssets($view,$widget_id)
    {
        $view->registerJs(<<<JS
if (window.activeDmstrBootstrapTabIds === undefined) {
  window.activeDmstrBootstrapTabIds = [];
}
if (window.activeDmstrBootstrapTabIds.indexOf("{$widget_id}") === -1) {
  window.activeDmstrBootstrapTabIds.push("{$widget_id}")
}

JS
,View::POS_HEAD);
        BootstrapAsset::register($view);
    }

    /**
     * @deprecated
     *
     * Remember active tab state for this URL
     */
    public static function rememberActiveState()
    {
       // Ensure backward compatibility
    }

    /**
     * Clear active tabs local storage cache
     */
    public static function clearLocalStorage()
    {
        Yii::$app->controller->view->registerJs('window.activeDmstrBootstrapTabIds = [];window.localStorage.clear("activeDmstrBootstrapTabs");');
    }
}