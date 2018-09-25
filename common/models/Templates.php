<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "templates".
 *
 * @property int $id
 * @property string $title Название
 * @property string $filename Имя файла
 * @property int $created_at Дата создания
 * @property int $updated_at Дата изменения
 */
class Templates extends \yii\db\ActiveRecord
{
    /**
     * @var string Содержимое шаблона
     */
    public $content;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * Получение полного пути до директории с файлами шаблонов
     *
     * @return string
     */
    public static function getDirectoryPath()
    {
        return Yii::getAlias('@common') . '/templates';
    }

    /**
     * Получение полного пути до файла шаблона
     *
     * @param string $fileName название файла без расширения
     * @return string
     */
    public static function getFilePath($fileName)
    {
        return self::getDirectoryPath() . '/' . $fileName . '.html';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'filename', 'content'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['title', 'filename'], 'trim'],
            ['title', 'unique'],
            ['title', 'string', 'max' => 128],
            ['filename', 'string', 'max' => 64],
            ['filename', 'filter', 'filter' => function ($value) {
                return strtolower($value);
            }],
            ['filename', 'match', 'pattern' => '/[a-zA-Z0-9_-]+$/'],
            ['filename', 'unique'],
            ['filename', 'validateFilename'],
            ['content', 'string'],
        ];
    }

    /**
     * Дополнительная валидация имения файла (проверка существования директории и файла)
     *
     * @param string $attribute
     * @param $params
     * @return bool
     */
    public function validateFilename($attribute, $params)
    {
        if (!self::isExistDirectory()) {
            $this->addError($attribute, 'Директория для хранения шаблонов не существует.');
        }

        if ($this->isNewRecord && $this->isExistFile($this->$attribute) === true) {
            $this->addError($attribute, 'Файл «' . $this->$attribute . '.html» уже существует.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'title'      => 'Название',
            'filename'   => 'Имя файла',
            'content'    => 'Содержимое шаблона',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * Проверка существования категории
     *
     * @param bool $create создать, если директория не существует
     * @return bool
     */
    public function isExistDirectory($create = true)
    {
        if (!file_exists(self::getDirectoryPath())) {
            // Попытка создать корневую директорию
            if ($create) {
                return mkdir(self::getDirectoryPath());
            }

            return false;
        }

        return true;
    }

    /**
     * Проверка существования файла шаблона
     *
     * @param string $filename
     * @return bool
     */
    public function isExistFile($filename)
    {
        return file_exists(self::getFilePath($filename));
    }

    /**
     * Получение содержимого из файла
     *
     * @param string $filename
     * @return null|string
     */
    private function loadFileData($filename)
    {
        if (!$this->isExistFile($filename)) {
            return null;
        }

        $fp = fopen(self::getFilePath($filename), 'r');
        $content = fread($fp, filesize(self::getFilePath($filename)));
        fclose($fp);
        if ($content === false) {
            $content = null;
        }

        return $content;
    }

    /**
     * Запись контента в файл
     *
     * @param string $filename
     * @param string $content
     * @return bool
     */
    private function writeFileData($filename, $content)
    {
        $fp = fopen(self::getFilePath($filename), 'w');
        if ($fp === false) {
            return false;
        }
        if (fwrite($fp, $content) === false) {
            fclose($fp);
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }

    /**
     * Создание файла шаблона
     *
     * @param string $filename
     * @param string $content содержимое для записи в файл
     * @return bool
     */
    public function createFile($filename, $content)
    {
        if ($this->isExistFile($filename)) {
            return false;
        }

        return $this->writeFileData($filename, $content);
    }

    /**
     * Обновление файла шаблона
     *
     * @param string $filename
     * @param string $content содержимое для записи в файл
     * @return bool
     */
    public function updateFile($filename, $content)
    {
        $tmpFilename = 'tmp_' . $filename;
        $oldFilename = 'old_' . $filename;

        // Проверка наличия временного файла, если он существует, попытка его удалить
        if ($this->isExistFile($tmpFilename)) {
            if (!$this->removeFile($filename)) {
                return false;
            }
        }

        // Если файла не существует, пробуем создать
        if (!$this->isExistFile($filename)) {
            return $this->createFile($filename, $content);
        }

        // Не удалось создать временный файл
        if (!$this->writeFileData($tmpFilename, $content)) {
            return false;
        }

        // Переименовываем текущий файл в устаревший (сохраняем содержимое, для дальнейшего восстановления)
        if (!$this->renameFile($filename, $oldFilename)) {
            return false;
        }

        // Переименовываем временный файл (с изменениями) в оригинальный
        if (!$this->renameFile($tmpFilename, $filename)) {
            // Пытаемся вернуть старое содержимое
            $this->renameFile($oldFilename, $filename);
            return false;
        }

        // Удаляем устаревший файл
        $this->removeFile($oldFilename);

        // Изменения успешно записаны
        return true;
    }

    /**
     * Переименование файла шаблона
     *
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public function renameFile($oldName, $newName)
    {
        if ($this->isExistFile($oldName)) {
            return rename(self::getFilePath($oldName), self::getFilePath($newName));
        } else {
            return false;
        }
    }

    /**
     * Удалить файл шаблона
     *
     * @param string $filename
     * @return bool
     */
    public function removeFile($filename)
    {
        if ($this->isExistFile($filename)) {
            return unlink(self::getFilePath($filename));
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        // После поиска, получение содержимого шаблона
        $this->content = $this->loadFileData($this->filename);

        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (parent::save($runValidation, $attributeNames)) {
            // Создание или переименование/обновление фала
            if ($this->isNewRecord) {
                $fileCreated = $this->createFile($this->filename, $this->content);
            } else {
                $oldFilename = $this->getOldAttribute('filename');
                $newFilename = $this->filename;

                if (!empty($oldFilename) && $oldFilename != $newFilename) {
                    if (!$this->renameFile($oldFilename, $newFilename)) {
                        $this->addError('filename', 'Не удалось переименовать файл шаблона.');
                        return false;
                    }
                }

                $fileCreated = $this->updateFile($this->filename, $this->content);
            }

            if ($fileCreated === false) {
                $this->addError('content', 'Не удалось создать/изменить содержимое шаблона.');
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if (parent::delete()) {
            // Удаление файла
            $this->removeFile($this->filename);
            return true;
        } else {
            return false;
        }
    }
}
