<?php

namespace app\modules\imperavi\models;

/**
 * Class ImageUploadModel
 *
 * @package app\modules\imperavi\models
 */
class ImageUploadModel extends FileUploadModel
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['uploadDirectory', 'required'],
            ['file', 'file', 'extensions' => 'jpg,jpe,jpeg,png,gif,bmp'],
        ];
    }
}
