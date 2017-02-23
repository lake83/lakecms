<?php
namespace app\modules\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use app\modules\user\models\Permissions;
use yii\helpers\Json;

class Group extends ActiveRecord
{
    const CACHE_KEY = 'permissions';
    
    public $permissions = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'status'                
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['is_active', 'integer'],
            [['status', 'permissions'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'status' => 'Статус',
            'is_active' => 'Активно'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        
        $this->permissions = $this->getPermissions();
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Сохранение разрешений на действия
        $old_permissions = $this->getPermissions(false);
        $new_permissions = Yii::$app->request->post('Group')['permissions']; 
             
        if (!empty($new_permissions)) {
            foreach($new_permissions as $key => $permission) {
                $permissions = Permissions::findOne(['user_status' => $this->status, 'module' => $key]);
                if (!$permissions) {
                    $permissions = new Permissions;
                }
                $permissions->user_status = $this->status;
                $permissions->module = $key;
                $permissions->actions = Json::encode($permission);
                if ($permissions->save()) {
                    unset($old_permissions[$key]);
                }               
            }
        }
        if (!empty($old_permissions)) {
            foreach($old_permissions as $key => $permission) {
                $permissions = Permissions::findOne(['user_status' => $this->status, 'module' => $key]);
                $permissions->actions = '[]';
                $permissions->save();  
            }          
        }
        Yii::$app->cache->delete(self::CACHE_KEY.'_'.$this->status);
    }
    
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->where(['is_active' => 1])->all(),'status','title');
    }
    
    public function getPermissions($cache = true)
    {
        $data = Yii::$app->cache->get(self::CACHE_KEY.'_'.$this->status);
        if (!$cache || $data === false) {
            $actions = Permissions::find()->select('module,actions')->where(['user_status' => $this->status])->asArray()->all();
            foreach($actions as $action) {
                $data[$action['module']] = Json::decode($action['actions']);
            } 
            Yii::$app->cache->set(self::CACHE_KEY.'_'.$this->status, $data);
        } 
        return $data;
    }
}