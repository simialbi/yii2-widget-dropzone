<?php

namespace simialbi\yii2\dropzone;

use yii\web\AssetBundle;

class DropZoneAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/enyo/dropzone/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'min/dropzone.min.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'min/dropzone.min.js',
    ];
}
