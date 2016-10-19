<?php
/**
 * User: execut
 * Date: 25.07.16
 * Time: 9:59
 */

namespace execut\yii\base\action\adapter;


use execut\TestCase;
use execut\yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Request;
use yii\web\Response;

class DeleteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $_SERVER['HTTP_REFERER'] = 'test';
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $_SERVER['HTTP_REFERER'] = null;
    }

    public function testRun() {
        $action = new Delete();
        \yii::$app->layout = 'test';
        $action->modelClass = DeleteTestModel::className();
        $action->setActionParams([
            'get' => [
                'id' => 1,
            ],
            'module' => 'testModule',
            'controller' => 'testController',
            'action' => 'testAction'
        ]);

        $response = $action->run();

        $this->assertInstanceOf(Response::className(), $response->content);
        $this->assertEquals('test', $response->content->getHeaders()->get('Location'));

        $model = $action->model;
        $this->assertTrue($model->isDeleteCalled);
        $this->assertEquals(1, $model->pk);

        $this->assertEquals([
            'kv-detail-success' => 'Record ' . $model . ' deleted',
        ], $response->flashes);
    }
}

class DeleteTestModel extends ActiveRecord {
    public $isDeleteCalled = false;
    public $pk = null;
    public static function findByPk($pk) {
        $model = new self;
        $model->pk = $pk;
        return $model;
    }

    public function delete()
    {
        return $this->isDeleteCalled = true;
    }

    public function toString()
    {
        return 'deletedModel';
    }
}