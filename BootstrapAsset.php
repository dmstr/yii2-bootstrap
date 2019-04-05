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

use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@vendor/dmstr/yii2-bootstrap/assets/web';

    public $js = [
        'js/remember-tab.js'
    ];

    public $depends = [
        BootstrapPluginAsset::class
    ];
}
