<?php
/**
 * User: execut
 * Date: 15.07.16
 * Time: 10:28
 */

namespace execut\yii\base\action\adapter\helper;


use execut\yii\base\action\Adapter;
use execut\yii\base\action\adapter\Form;
use execut\yii\base\action\adapter\GridView;
use execut\TestCase;
use yii\base\Model;
use yii\web\UploadedFile;

class FormLoaderTest extends TestCase
{
    protected $filePath;
    public function setUp()
    {


        $testFilePath = tempnam('/tmp', 'test_');
        file_put_contents($testFilePath, 'test');
        $this->filePath = $testFilePath;
        UploadedFile::reset();

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        unlink($this->filePath);
        UploadedFile::reset();
    }

    public function testNotValid()
    {
        $helper = new FormLoader();
        $helper->model = new TestModel();
        $helper->data = [
            '' => '',
        ];

        $this->assertFalse($helper->run());
    }

    public function testValid() {
        $helper = new FormLoader();

        $helper->model = new TestModel();
        $helper->data = [
            'attribute' => 'test',
        ];

        $result = $helper->run();
        $this->assertTrue($result);
    }

    public function testFileUpload() {
        $helper = new FormLoader();
        $helper->filesAttributes = [
            'testContentFile' => 'testFile',
        ];

        $helper->data = [
            'blabla' => 'blabla',
        ];
        $model = new TestFileModel();
        $helper->model = $model;

        $_FILES['testFile'] = [
            'name' => 'testFile',
            'tmp_name' => $this->filePath,
            'type' => 'txt',
            'size' => 4,
            'error' => null,
        ];

        $this->assertTrue($helper->run(), '[check what form is validated ' . var_export($model->errors, true));
        $this->assertInstanceOf(UploadedFile::className(), $model->testFile);
        $this->assertEquals('test', $model->testContentFile);
    }
}

class TestModel extends Model {
    public $attribute;
    protected $dataProvider = null;
    public function rules() {
        return [
            [['attribute'], 'required']
        ];
    }

    public function setDataProvider($dp) {
        $this->dataProvider = $dp;
    }

    public function getDataProvider() {
        return $this->dataProvider;
    }

    public function formName()
    {
        return '';
    }
}

class TestFileModel extends TestModel {
    public $testFile = null;
    public $testContentFile = null;
    public function rules() {
        return [
            [['testContentFile'], 'required'],
            [['testFile'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function formName()
    {
        return '';
    }
}