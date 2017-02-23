<?php

namespace app\modules\translations\controllers;

use Yii;
use app\controllers\AdminController;
use yii\data\ArrayDataProvider;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use app\modules\translations\models\TranslateModels;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\base\DynamicModel;

/**
 * TranslateModelsController implements the CRUD actions for TranslateModels model.
 */
class TranslateModelsController extends AdminController
{
    /**
     * Вывод в GridView всех переводов указанных в функции getTranslations() модулей
     * @return string
     */
    public function actionIndex()
    {
        $resultData = [];
        foreach(Yii::$app->modules as $key => $module)
        {
            if (!is_object($module))
                $module = Yii::$app->getModule($key);

            if (isset($module->translations) && $module->translations) {
                foreach($module->translations as $model => $one) {
                    $attributes = '';
                    foreach($one['attributes'] as $attribute) {
                        $attributes.= end($one['attributes']) == $attribute ? $attribute['label'] : $attribute['label'].', ';
                    }
                    $resultData[] = ['module_id' => $key, 'model_id' => $model, 'module' => $module->title, 'model' => $one['title'], 'attributes' => $attributes];
                }
            }    
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $resultData,
            'sort' => [
                'attributes' => ['module', 'model']
            ]
        ]); 
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
    
    /**
     * Вывод в GridView всех записей модели по указанным ID модуля и модели
     * @param string $module_id ID модуля
     * @param string $model_id ID модели
     * @return string
     */
    public function actionList($module_id, $model_id)
    {
        $model = 'app\modules\\'.$module_id.'\models\\'.$model_id.'Search';
        $searchModel = new $model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $module = Yii::$app->getModule($module_id);
        $columns = ArrayHelper::getColumn($module->translations[$model_id]['attributes'], 'id');
        
        foreach($dataProvider->getModels() as $model) {
            foreach($model as $key => $attr) {
                if ($key == 'id' || in_array($key, $columns))
                    $model[$key] = StringHelper::truncateWords(strip_tags($attr), 7);
                else
                    unset($model[$key]);
            }
        }
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns
        ]);
    }
    
    /**
     * Вывод и сохранение переводов для родительской записи
     * @param string $module_id ID модуля
     * @param string $model_id ID модели
     * @param intager $id ID записи
     * @return string
     * @throws NotFoundHttpException если запись не найдена
     * @throws ServerErrorHttpException если запись не сохранена
     */
    public function actionTranslate($module_id, $model_id, $id)
    {
        $owner = '\app\modules\\'.$module_id.'\models\\'.$model_id;
        if (!$owner = $owner::findOne($id))
            throw new NotFoundHttpException('Страница не найдена.');
            
        $translations = Yii::$app->getModule($module_id)->translations[$model_id];
        
        $fields = [];
        $data = $this->loadModel($module_id, $model_id, $id);
        
        foreach(ArrayHelper::getColumn($translations['attributes'], 'id') as $field) {
            foreach(Yii::$app->params['languages'] as $key => $lang) {
                if ($key !== Yii::$app->sourceLanguage) {
                    $old_record = $this->old_record($data, $field, $key);
                    $fields[$field.'___'.$key] = $old_record ? $old_record['value'] : '';
                }
            }
        }
        $model = new DynamicModel($fields);
        
        if ($owner->load(Yii::$app->request->post()) && $model = Yii::$app->request->post('DynamicModel')) {
            $connection = Yii::$app->db;
            $transaction = $owner::getDb()->beginTransaction();
            try {
                if ($owner->save()) {
                    foreach($model as $field => $record) {
                        $field = explode('___', $field);
                        $old_record = $this->old_record($data, $field[0], $field[1]);
                        if ($old_record && $record !== $old_record['value'])
                            $connection->createCommand('UPDATE translate_models SET value=:value WHERE id=:id', [':value' => $record, ':id' => $old_record['id']])->execute();
                        elseif (!$old_record && !empty($record)) {
                            $connection->createCommand('INSERT INTO translate_models (`module`,`model`,`owner_id`,`column`,`lang`,`value`) VALUES(:module,:model,:owner_id,:column,:lang,:value)',
                                [':module' => $module_id, ':model' => $model_id, ':owner_id' => $id, ':column' => $field[0], ':lang' => $field[1], ':value' => $record])->execute();
                        }                    
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Изменения сохранены.');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } catch(Exception $e) {
                $transaction->rollback();
                throw new ServerErrorHttpException($e->getMessage());
            }
        }
        return $this->render('translate', ['model' => $model, 'owner' => $owner, 'translations' => $translations]);
    }
    
    /**
     * Удаление всех переводов родительськой записи
     * @param string $module_id ID модуля
     * @param string $model_id ID модели
     * @param intager $id ID записи
     * @return string
     */
    public function actionDelete($module_id, $model_id, $id)
    {
        if (Yii::$app->db->createCommand()->delete('translate_models', ['id' => ArrayHelper::getColumn($this->loadModel($module_id, $model_id, $id),'id')])->execute())
            Yii::$app->session->setFlash('success', 'Перевод удален.');
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    /**
     * Проверяет существование записи для обновления
     * @param array $data записи
     * @param string $column поле модели
     * @param string $lang язык
     * @return array $result найденные записи
     */
    private function old_record($data, $column, $lang)
    {
        $result = false;
        foreach($data as $val) {
            if ($val['column'] == $column && $val['lang'] == $lang) {
                $result = $val;
                break;
            }   
        }
        return $result;
    }
    
    /**
     * Выбор всех записей по модулю, модели и ID родительской записи
     * @param string $module_id ID модуля
     * @param string $model_id ID модели
     * @param intager $id ID записи
     * @return array
     */
    private function loadModel($module_id, $model_id, $id)
    {
        return TranslateModels::find()->select('id,column,lang,value')->where(['module' => $module_id, 'model' => $model_id, 'owner_id' => $id])->asArray()->all();
    }
}