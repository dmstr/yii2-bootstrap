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
     * @param $route
     * @param $controllerId
     */
    public static function rememberActiveTab($controllerId)
    {
        self::registerAssets();
$js = <<<JS
            //var controllerId = "{$controllerId}";
            if (history.state) {
                currentUrl = history.state.url;
            } else {
                currentUrl = document.location.href;
            }
            currentUrl = document.URL;
            var controllerId = currentUrl
                    .toLowerCase()
                    .replace(/ /g,'-')
                    .replace(/[^\w-]+/g,'');

console.log(history);

            function setCookie(elem) {
                currentUrl = document.URL;
                var controllerId = currentUrl
                    .toLowerCase()
                    .replace(/ /g,'-')
                    .replace(/[^\w-]+/g,'');
                var activeTab     = jQuery(elem).attr("href");
                jQuery.cookie.raw = true;
                jQuery.cookie("_bs_activeTab_" + controllerId, activeTab, { path: "/" });
                console.log(document.URL);
            }

            function initialSelect() {
                currentUrl = document.URL;
                var controllerId = currentUrl
                    .toLowerCase()
                    .replace(/ /g,'-')
                    .replace(/[^\w-]+/g,'');
                var activeTab = jQuery.cookie("_bs_activeTab_" + controllerId);
                if (activeTab !== "") {
                    jQuery("[href=" + activeTab + "]").tab("show");
                }
            }

            jQuery("#relation-tabs > li > a").on("click", function (event) {
                setCookie(this);
                console.log('tab click');
            });

            $(document).on('pjax:end', function() {
               setCookie($('#relation-tabs .active A'));
               console.log('pjax end');
             } );

            jQuery(window).on("load", function () {
               initialSelect();
               console.log('load');
            } );

            console.log('AJAX');
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