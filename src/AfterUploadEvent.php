<?php
/**
 * @package yii2-dropzone
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace simialbi\yii2\dropzone;

use yii\base\Event;
use yii\web\UploadedFile;

/**
 * AfterUploadEvent represents the information available in [[UploadAction::EVENT_AFTER_UPLOAD]].
 *
 * @author Simon Karlen <simi.albi@outlook.com>
 * @since 2.0
 */
class AfterUploadEvent extends Event
{
    /**
     * @var UploadedFile The uploaded file instance that was handled.
     */
    public UploadedFile $file;

    /**
     * @var string The full path of the saved file.
     */
    public string $path;

    /**
     * @var string The full qualified url of the saved file.
     */
    public string $url;

    /**
     * @var array|mixed|object The post data sent with the file.
     */
    public $post;
}
