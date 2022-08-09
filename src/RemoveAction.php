<?php

namespace simialbi\yii2\dropzone;

use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;

/**
 * The remove action handles the deletion of an previously uploaded file.
 */
class RemoveAction extends Action
{
    /**
     * @event After remove event
     */
    const EVENT_AFTER_REMOVE_FILE = 'afterRemoveFile';

    /**
     * @var string The full path of the uploaded file.
     */
    public string $uploadDir = '@webroot/upload';

    /**
     * Runs the actions. Deletes the file and optionally calls a callback.
     *
     * @param string $fileName The file to delete.
     *
     * @return bool
     */
    public function run(string $fileName): bool
    {
        $path = FileHelper::normalizePath(Yii::getAlias($this->uploadDir . '/' . $fileName));
        $return = FileHelper::unlink($path);

        $event = new AfterRemoveFileEvent();
        $event->path = $path;

        $this->trigger(self::EVENT_AFTER_REMOVE_FILE, $event);

        return $return;
    }
}
