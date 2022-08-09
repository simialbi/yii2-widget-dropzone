<?php
/**
 * @package yii2-dropzone
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace simialbi\yii2\dropzone;

use yii\base\Event;


/**
 * AfterRemoveFileEvent represents the information available in [[RemoveAction::EVENT_AFTER_REMOVE_FILE]].
 *
 * @author Simon Karlen <simi.albi@outlook.com>
 * @since 2.0
 */
class AfterRemoveFileEvent extends Event
{
    /**
     * @var string The full path of the saved file.
     */
    public string $path;
}
