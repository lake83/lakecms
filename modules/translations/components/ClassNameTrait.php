<?php
namespace app\modules\translations\components;

/**
 * Получает ID модуля или модели из имени класса
 */
trait ClassNameTrait
{
    /**
     * @param string $className
     * @return string
     */
    private function getShortClassName($className, $module = false)
    {
        if (!$module) {
            return substr($className, strrpos($className, '\\') + 1);
        } else {
            $path = explode('\\', $className);
            return is_array($path) && array_key_exists(2, $path) ? $path[2] : null;
        }
    } 
}