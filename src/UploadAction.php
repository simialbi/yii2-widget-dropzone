<?php

namespace simialbi\yii2\dropzone;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Upload Action handles a File upload by the dropzone widget.
 */
class UploadAction extends Action
{
    /**
     * @event After upload event
     */
    const EVENT_AFTER_UPLOAD = 'afterUpload';

    /**
     * @var string The file name of the uploaded file.
     */
    public string $fileName = 'file';
    /**
     * @var string The path where to upload the file. The base Path will be `@webroot`.
     */
    public string $upload = 'upload';


    /**
     * @var string The full path of the uploaded file.
     */
    protected string $uploadDir = '';
    /**
     * @var string The url of the uploaded file.
     */
    protected string $uploadSrc = '';

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();

        $this->uploadDir = Yii::getAlias('@webroot/' . $this->upload . '/');
        $this->uploadSrc = Yii::getAlias('@web/' . $this->upload . '/');
    }

    /**
     * Sets the upload directory
     *
     * @param string $upload
     *
     * @return void
     */
    public function setUpload(string $upload): void
    {
        $this->upload = $upload;

        $this->uploadDir = Yii::getAlias('@webroot/' . $this->upload . '/');
        $this->uploadSrc = Yii::getAlias('@web/' . $this->upload . '/');
    }

    /**
     * Runs the action. Saves the uploaded file and returns the full path. An [[EVENT_AFTER_UPLOAD]] will be triggered
     * after upload.
     *
     * @return string
     *
     * @throws HttpException|Exception
     */
    public function run(): string
    {
        $file = UploadedFile::getInstanceByName($this->fileName);
        if ($file->hasError) {
            throw new HttpException(500, 'Upload error');
        }

        FileHelper::createDirectory($this->uploadDir);

        $fileName = $file->name;
        if (file_exists($this->uploadDir . $fileName)) {
            $fileName = $file->baseName . '-' . uniqid() . '.' . $file->extension;
        }
        $filePath = FileHelper::normalizePath($this->uploadDir . $fileName);
        $file->saveAs($filePath);

        $event = new AfterUploadEvent();
        $event->file = $file;
        $event->path = $filePath;
        $event->url = $this->uploadSrc . $fileName;
        $event->post = Yii::$app->request->post();

        $this->trigger(self::EVENT_AFTER_UPLOAD, $event);

        return $filePath;
    }
}
