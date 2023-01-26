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
     * @var string the default icon
     */
    public string $fileIcon = 'data:image/png;base64,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';

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
    jQuery('#{$this->options['id']}')[0].dropzone.displayExistingFile({name: files[i].name, size: files[i].size}, files[i].url || '{$this->fileIcon}');
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

        $view->registerJs('Dropzone.autoDiscover = false;', $view::POS_END, 'dropzone-auto-discover');

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
                $js[] = "jQuery('#$id')[0].dropzone.on('$event', $handler);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}
