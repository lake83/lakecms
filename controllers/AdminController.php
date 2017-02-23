<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\CmsHelper;
use yii\base\DynamicModel;
use yii\helpers\FileHelper;
use yii\caching\TagDependency;

/**
 * AdminController implements all admin controllers.
 */
class AdminController extends Controller
{
    /**
     * @var string адрес шаблона административной панели
     */
    public $layout = '@app/views/admin/layouts/main';
    
    /**
     * @var string адрес папки с экшенами контроллера
     */
    public $actionsPath = 'app\controllers\actions\\';
    
    /**
     * @var string адрес папки с моделями в модуле
     */
    public $modelPath;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->modelPath = 'app\modules\\'.$this->module->id.'\models\\';
        // экшн для обработки ошибок в админ панели
        Yii::$app->errorHandler->errorAction = 'admin/error';
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['error'],
                'rules' => [
                    [
                       'actions' => ['settings', 'create-setting', 'clear-cache', 'clear-assets'],
                       'allow' => true,
                       'roles' => ['@']
                    ],
                    [
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function() {
                           return CmsHelper::can('/'.$this->module->id.'/'.$this->id.'/'.$this->action->id);                    
                       }
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-all' => ['post'],
                    'logout' => ['post']
                ],
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
        ];
    }
    
    /**
     * Редактирование настроек в админ панели
     * @return array|object
     */
    public function actionSettings()
    {
        $request = Yii::$app->request;
        $model = $request->post('DynamicModel');
                
        if ($model && Yii::$app->db->createCommand('UPDATE settings SET value="'.array_values($model)[0].'" WHERE name="'.array_keys($model)[0].'"')->execute()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            TagDependency::invalidate(Yii::$app->cache, 'settings');         
            return [
                'message' => '<div class="alert-success alert fade in">'.Yii::t('app', 'Изменения сохранены.').'</div>',
                'name' => array_keys($model)[0],
                'value' => array_values($model)[0]
            ];
        }
        if ($request->isAjax && $field = $request->post('field')) {
            $param = (new \yii\db\Query())->from('settings')->where(['name' => $field])->one();
            $model = new DynamicModel([$field]);
            $model->addRule($field, 'required')->addRule($field, $param['rules']);
            return $this->renderAjax('settings', ['model' => $model, 'param' => $param]);            
        }
    }
    
    /**
     * Создание настроек в админ панели
     * @return string|object
     */
    public function actionCreateSetting()
    {
        if ($model = Yii::$app->request->post('DynamicModel')) {
            if (Yii::$app->db->createCommand()->insert('settings', $model)->execute()) {
                TagDependency::invalidate(Yii::$app->cache, 'settings');
                echo '<div class="alert-success alert fade in">'.Yii::t('app', 'Изменения сохранены.').'</div>';
            }
            Yii::$app->end();
        }
        if (Yii::$app->request->isAjax) {
           $model = new DynamicModel(['name', 'value', 'label', 'icon', 'rules', 'hint']);
           $model->addRule(['name', 'value', 'label'], 'required')->addRule(['icon', 'rules', 'hint'], 'string');
           return $this->renderAjax('createSetting', ['model' => $model]); 
        }
    }
    
    /**
     * Очистка всего кеша приложения
     * @return string
     */
    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Очистка кеша успешно завершена.'));
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    /**
     * Очистка ресурсов assets приложения
     * @return string
     */
    public function actionClearAssets()
    {
        $dirs = glob(Yii::getAlias('@webroot/assets').'/*', GLOB_ONLYDIR);

        foreach ($dirs as $value) {
            FileHelper::removeDirectory($value);
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Очистка ресурсов успешно завершена.'));
        return $this->redirect(Yii::$app->request->referrer);
    }
}