<?php

namespace simialbi\yii2\dropzone;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 *
 */
class DropZone extends InputWidget
{
    /**
     * @var string The url where to upload the files.
     */
    public string $url;

    /**
     * @var array An array of already existing files on the server. The file must have at least this structure:
     * ```
     * [
     *     'name' => 'MyFile.jpg',
     *     'size' => 1024, // in bytes
     *     'url' => 'https://example.com/MyFile.jpg'
     * ]
     * ```
     */
    public array $storedFiles = [];

    /**
     * @var array the options for the underlying DropZone Plugin.
     * @see https://docs.dropzone.dev/configuration/basics/configuration-options
     */
    public array $clientOptions = [];

    /**
     * @var array the event handlers for the underlying Bootstrap JS plugin.
     * @see https://docs.dropzone.dev/configuration/events
     */
    public array $clientEvents = [];

    /**
     * @var string The name of the dropzone
     */
    protected string $dropzoneName = 'dropzone';

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!isset($this->url)) {
            throw new InvalidConfigException('The "url" param must be set.');
        }

        Html::addCssClass($this->options, 'dropzone');
        $this->dropzoneName = 'dropzone_' . $this->options['id'];
    }

    /**
     * Runs the widget
     *
     * @return string
     */
    public function run(): string
    {
        $view = $this->getView();

        $html = Html::tag('div', '', $this->options);

        DropZoneAsset::register($view);
        $this->registerPlugin();
        $this->addFiles($this->storedFiles);

        return $html;
    }

    /**
     * Add already existing files
     *
     * @param array $files The array of files. The file must have at least this structure:
     * ```
     * [
     *     'name' => 'MyFile.jpg',
     *     'size' => 1024, // in bytes
     *     'url' => 'https://example.com/MyFile.jpg'
     * ]
     * ```
     * @return void
     */
    protected function addFiles(array $files = []): void
    {
        if (!empty($files)) {
            $files = Json::encode($files);
            $js = <<<JS
var files = $files;
for (var i = 0; i < files.length; i++) {
    jQuery('#{$this->options['id']}').dropzone.displayExistingFile([{name: files[i].name, size: files[i].size}], files[i].url);
}
JS;
            $this->view->registerJs($js);
        }
    }

    /**
     * Registers a the plugin and the related events
     *
     * @return void
     */
    protected function registerPlugin(): void
    {
        $view = $this->getView();

        if (empty($this->name) && (!empty($this->model) && !empty($this->attribute))) {
            $this->name = Html::getInputName($this->model, $this->attribute);
        }
        $options = ArrayHelper::merge([
            'url' => $this->url,
            'paramName' => $this->name,
            'params' => []
        ], $this->clientOptions);
        if (Yii::$app->request->enableCsrfValidation) {
            $options['params'][Yii::$app->request->csrfParam] = Yii::$app->request->csrfToken;
        }

        $id = $this->options['id'];
        $options = Json::htmlEncode($options);
        $js = "jQuery('#$id').dropzone($options);";
        $view->registerJs($js);

        $this->registerClientEvents();
    }

    /**
     * Registers JS event handlers that are listed in [[clientEvents]].
     *
     * @return void
     */
    protected function registerClientEvents(): void
    {
        if (!empty($this->clientEvents)) {
            $id = $this->options['id'];
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}
