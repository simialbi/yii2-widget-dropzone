Dropzone Extension for Yii 2
==============================

This extension provides the [Dropzone](http://www.dropzonejs.com/) integration for the Yii2 framework.

> This fork is based on @DevGroup-ru's extension [yii2-dropzone](https://github.com/DevGroup-ru/yii2-dropzone).

Installation
------------

This extension requires [Dropzone](https://github.com/enyo/dropzone)

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist simialbi/yii2-widget-dropzone "*"
```

or add

```
"simialbi/yii2-widget-dropzone": "*"
```

to the require section of your composer.json.


General Usage
-------------

```php
use simialbi\yii2\dropzone\DropZone;

DropZone::widget(
    [
        'name' => 'file', // input name or 'model' and 'attribute'
        'url' => '', // upload url
        'storedFiles' => [], // stores files
        'clientOptions' => [], // dropzone js options
        'clientEvents' => [], // dropzone event handlers
        'options' => [] // container html options
    ]
)
```

you can also register `simialbi\yii2\dropzone\UploadAction` and `simialbi\yii2\dropzone\RemoveAction` actions in your controller
