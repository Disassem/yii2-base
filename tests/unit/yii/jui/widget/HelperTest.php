<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/3/17
 * Time: 4:16 PM
 */

namespace execut\yii\jui\widget;

use execut\TestCase;
use yii\base\Widget;
use yii\web\AssetBundle;

class HelperTest extends TestCase
{
    public function testGetBundleClass() {
        $helper = $this->createHelper();
        $this->assertEquals(HelperTestWidgetAsset::class, $helper->getBundleClass());
    }

    public function testGetDefaultCssClass() {
        $helper = $this->createHelper();
        $this->assertEquals('helper-test-widget', $helper->getDefaultCssClass());
    }

    public function testRegisterBundle() {
        $helper = $this->createHelper();
        $helper->registerBundle();

        $this->assertTrue(HelperTestWidgetAsset::$registerIsCalled);
    }

    public function testGetDefaultJsWidgetName() {
        $helper = $this->createHelper();
        $this->assertEquals('HelperTestWidget', $helper->getDefaultJsWidgetName());
    }

    /**
     * @return Helper
     */
    protected function createHelper(): Helper
    {
        $widget = new HelperTestWidget();
        $helper = new Helper([
            'widget' => $widget
        ]);
        return $helper;
    }
}

class HelperTestWidget extends Widget {
    public $view = 'test';
}

class HelperTestWidgetAsset extends AssetBundle {
    public static $registerIsCalled = false;
    public static function register($view)
    {
        if ($view === 'test') {
            self::$registerIsCalled = true;
        }
    }
}