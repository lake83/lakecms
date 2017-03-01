<?php
namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use app\modules\event\models\MailTemplates;
use app\modules\event\models\MailQueue;

class CmsHelper
{
    /**
     * Вывод фильтра и значения в колонке "Активно" GridView
     * @param object $searchModel
     * @return array
     */
    public static function is_active($searchModel)
    {
        return [
            'class' => 'pheme\grid\ToggleColumn',
            'attribute' => 'is_active',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'is_active',
                [0 => 'Не активно', 1 => 'Активно'],
                ['class' => 'form-control', 'prompt' => '- выбрать -']
            ),
            'onText' => 'Вкл',
            'offText' => 'Выкл'
        ];
    }
    
    /**
     * Вывод фильтра и значения в колонке "Создан" GridView
     * @param object $searchModel
     * @return array
     */
    public static function created_at($searchModel)
    {
        return [
            'attribute' => 'created_at',
            'format' => ['date', 'php:j M, G:i'],
            'filter' => \yii\jui\DatePicker::widget([
                'model'=>$searchModel,
                'options' => ['class' => 'form-control'],               
                'attribute'=>'created_at',
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
            ])
        ];
    }
    
    /**
     * Видимость пунктов бокового меню в зависимости от права пользователя на действие
     * @param array $items
     * @return array
     */
    public static function is_item_visible($items)
    {
        foreach($items as $key => $item)
        {
            if ($item['url'] !== '#')
                if (!static::can($item['url'][0]))
                    $items[$key]['visible'] = false;
            if (isset($item['items']))
                $items[$key]['items'] = static::is_item_visible($item['items']);
        }
        return $items;
    }
    
    /**
     * Строка настроек как массив
     * @param string $settings строка настроек вида 'ключ=>значение,ключ=>значение'
     * @return array настройка как массив
     */
    public static function settings_array($settings)
    {
        $items = preg_split('/[=>,]+/', $settings, -1, PREG_SPLIT_NO_EMPTY);
        
        if (is_array($items)) {
            foreach($items as $key => $item) 
                if ($key % 2)
                    $result[trim($items[$key-1])] = $item; 
        }
        else
            $result = [];
        return $result;
    }
    
    /**
     * Ссылка на первый из подключенных модулей исключая не выводимые в общем списке модулей и не допустимые для пользователя по правам,
     * при отсутствии результата false для $redirect = true ссилка на модуль User
     * @param boolean $redirect ссылка для редиректа
     * @return array|boolean
     */
    public static function modules_link($redirect = false)
    {
        foreach(array_keys(Yii::$app->modules) as $module) {
            $module = Yii::$app->getModule($module);
            if (isset($module->title) && $module->menu['modules_show'] && is_array($url = $module->menu['items'][0]['url'])) {
                if (static::can($module->menu['items'][0]['url'][0]))
                    return $module->menu['items'][0]['url'];
            } 
        }
        return !$redirect ? ['/user/user/index'] : false;
    }
    
    /**
     * Вывод в структуре страницы с блоками
     * @param boolean $scheme вывод схемы блоков
     * @param array $use_blocks блоки занятые на странице
     * @param integer $rows количество блоков
     * @param string $id атрибут ID блока
     * @return string
     */
    public static function structure_blocks($scheme, $use_blocks, $rows = 5, $id = '@content')
    {
        $blocks = '';
        for ($i = 1; $i <= $rows; $i++) {
            if ($scheme)
                $blocks .= '<div id="'.$id.$i.'" class="content-block">';
            
            if (!empty($use_blocks)) {
                foreach($use_blocks as $key => $block) {
                    if ($block['position'] == $id.$i) {
                        if ($scheme)
                            $blocks .= '<span class="tip">'.($block['type'] == 1 ?
                                Yii::t('app', 'Контентный блок').' "'.$block['contentblock']['title'].'"' :
                                Yii::t('app', 'Виджет').' "'.$block['widget_action'].'"').'</span>';
                            
                        $blocks .= $block['type'] == 1 ? Yii::$app->controller->renderPartial('@app/modules/contentblock/views/contentblock/layouts/'.$block['layout'], 
                            ['model' => $block['contentblock']]
                        ) : trim(VarDumper::export(Yii::$app->runAction($block['widget_action'], Yii::$app->request->queryParams)), "'");
                        unset($use_blocks[$key]);
                        break;
                    }
                }
            }
            if ($scheme)
                $blocks .= '</div>'.PHP_EOL;
        }
        return $blocks;
    }
    
    /**
     * Проверка разрешено ли пользователю действие
     * @param string $url адрес действия вида /module ID/controller ID/action ID
     * @return boolean
     */
    public static function can($url)
    {
        if (is_array($path = explode('/', $url)) && (isset($path[1]) && isset($path[2]) && isset($path[3])))
            return is_array($actions = Yii::$app->user->identity->group->permissions[$path[1]][$path[2]]) ? in_array($path[3], $actions) : false;
    }
    
    /**
     * Ресайз изображения
     * @param string $image изображение
     * @param int $width ширина
     * @param mixed $height высота  
     * @return string Url изображения
     */
    public static function resized_image($image = '', $width, $height = '')
    {
        $url = false;
        if (!empty($image)) {
            $image = explode('/', $image);
            $last = end(array_keys($image));
            $file = end($image);
            unset($image[$last]);
            $dir_path = !empty($image) ? '/' . implode('/', $image) : '';
            $dir = Yii::getAlias('@webroot/images/uploads/') . $width . 'x' . $height . $dir_path;
            $img = $dir . '/' . $file;
            
            if (file_exists($img)) {
                $url = true;
            } else {
                FileHelper::createDirectory($dir);
                $original = Yii::getAlias('@webroot/images/uploads/source') . $dir_path . '/' . $file;
                try {
                    if (file_exists($original) && filesize($original) < 10000000) {
                        Image::thumbnail($original, $width, $height)->save($img, ['quality' => 100]);
                    }
                    $url = true;
                } catch (ErrorException $e) {
                    $url = false;
                }
            }
        }
        return $url ? '/images/uploads/' . $width . 'x' . $height . $dir_path . '/' . $file : '/images/anonymous.png';
    }
    
    /**
     * Отправка email с использованием шаблонов из модуля event
     * @param string $template алиас шаблона в водуле event
     * @param string $email адрес получателя
     * @param string $params параметры письма
     * @return boolean true если письмо отправлено и сохранено в архив MailQueue
     */
    public static function sendMail($template, $email, $params = null)
    {
        if ($template = MailTemplates::find()->where(['slug' => $template, 'is_active' => 1])->localized()->asArray()->one()) {
            if (is_array($params)) {
                foreach($params as $key => $param) {
                    $regexps[sprintf(MailTemplates::VAR_TEMPLATE, $key)] = Html::encode($param);
                }
                // замена переменных [var] в тексте на значения, абсолютние пути для изображений
                $letter = preg_replace(array_keys($regexps), array_values($regexps), str_replace ('../..', Yii::$app->request->hostinfo, $template['text']));
            }
            $mailer = Yii::$app->mailer;
            $mailer->htmlLayout = '@app/modules/event/views/mail/layouts/html';
            $mailer->textLayout = '@app/modules/event/views/mail/layouts/text';
            $mailer->viewPath = '@app/modules/event/views/mail';
                        
            if ($mailer->compose(['html' => 'mail-html', 'text' => 'mail-text'], ['letter' => $letter])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($email)
                ->setSubject($template['subject'])
                ->send()) {
                $message = new MailQueue;
                $message->subject = $template['subject'];
                $message->text = $letter;
                $message->to = $email;
                if ($message->save()) {
                    return true;
                }
            }
        }
        return false;
    }
}
?>