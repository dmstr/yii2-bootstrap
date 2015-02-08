<?php

namespace dmstr\bootstrap;

use yii\web\View;

/**
 * @inheritdoc
 */
class Tabs extends \yii\bootstrap\Tabs
{
    /**
     * Register assetBundle
     */
    public static function registerAssets()
    {
        BootstrapAsset::register(\Yii::$app->controller->getView());
    }

    /**
     * Remember active tab state for this URL
     */
    public static function rememberActiveState()
    {
        self::registerAssets();
$js = <<<JS
            function getControllerId() {
                currentUrl = document.URL;
                return currentUrl
                        .toLowerCase()
                        .replace(/ /g,'-')
                        .replace(/[^\w-]+/g,'');
            }

            function setCookie(elem) {
            console.log(elem);
                var activeTab     = jQuery(elem).attr("href");
                jQuery.cookie.raw = true;
                jQuery.cookie("_bs_activeTab_" + getControllerId(), activeTab, { path: "/" });
            }

            function initialSelect() {
                var activeTab = jQuery.cookie("_bs_activeTab_" + getControllerId());
                if (activeTab !== "") {
                    jQuery("[href=" + activeTab + "]").tab("show");
                }
            }

            jQuery("#relation-tabs > li > a").on("click", function (event) {
                setCookie(this);
            });

            jQuery(document).on('pjax:end', function() {
               setCookie($('#relation-tabs .active A'));
            });

            jQuery(window).on("load", function () {
               initialSelect();
            });
JS;

        if (\Yii::$app->request->isAjax) {
            echo "<script type='text/javascript'>{$js}</script>";
        } else {

        // Register cookie script
        \Yii::$app->controller->getView()->registerJs(
            $js,
            View::POS_READY,
            'rememberActiveTab'
        );
        }
    }

    /**
     * @param $controllerId
     *
     * @return \yii\web\Cookie
     */
    public static function getParentRelationRoute($controllerId)
    {
        \Yii::$app->request->enableCookieValidation = false;

        if (isset(\Yii::$app->request->cookies["_bs_route_" . $controllerId])) {
            return \Yii::$app->request->cookies["_bs_route_" . $controllerId]->value;
        } else {
            return null;
        }
    }

    /**
     * @param $controllerId
     *
     * @return null|string
     */
    public static function getParentRelationActiveTab($controllerId)
    {
        \Yii::$app->request->enableCookieValidation = false;

        if (isset(\Yii::$app->request->cookies["_bs_activeTab_" . $controllerId])) {
            return \Yii::$app->request->cookies["_bs_activeTab_" . $controllerId]->value;
        } else {
            return null;
        }
    }

    public static function clearParentRelationRoute($controllerId)
    {
        self::registerAssets();

        // Register delete cookie script
        \Yii::$app->controller->getView()->registerJs(
            '
            var controllerId  = "' . $controllerId . '";
            jQuery.cookie.raw = true;
            jQuery.removeCookie("_bs_activeTab_" + controllerId, { path: "/" });
            jQuery.removeCookie("_bs_route_" + controllerId, { path: "/" });
            ',
            View::POS_READY,
            'clearParentRelationRoute'
        );
    }

}