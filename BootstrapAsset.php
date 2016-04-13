<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Class BootstrapAsset
 *
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */

namespace dmstr\bootstrap;

use yii\web\AssetBundle;
use yii\web\View;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    public function init()
    {

        parent::init();

        // Register global functions
        $js = <<<JS

/**
  * localStorage test, if not available,
  * feature rememberActiveState has no function
 */
var hasStorage = (function() {
  try {
    window.localStorage.setItem('tabTest', 'tabTest');
    window.localStorage.removeItem('tabTest');
    return true;
  } catch (exception) {
    return false;
  }
}());

// get sluged dockument URL
function getControllerId() {
    var currentUrl = document.URL;
    return currentUrl
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'');
}
// set localstorage item for an element
function setStorage(elem) {
    var activeTab     = jQuery(elem).attr("href");
    if (hasStorage) {
        window.localStorage.setItem("_bs_activeTab_" + getControllerId(), activeTab);
    }
}
// get this localstorage item
function initialSelect() {
    if (hasStorage) {
        var activeTab = window.localStorage.getItem("_bs_activeTab_" + getControllerId());
        if (activeTab !== "") {
            jQuery("[href='" + activeTab + "']").tab("show");
        }
    }
}
JS;
        \Yii::$app->controller->getView()->registerJs(
            $js,
            View::POS_HEAD,
            'TabsGlobalFunctions'
        );
    }
}
