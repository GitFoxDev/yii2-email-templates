<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Таблица с пользователями
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Создание тестового пользователя
        $userParams = Yii::$app->params['testUser'];
        $user = new \common\models\Users();
        $user->username = $userParams['username'];
        $user->email    = $userParams['email'];
        $user->setPassword($userParams['password']);
        echo '        > тестовый пользователь ' . $userParams['username'] . '@' . $userParams['password'];
        if ($user->save()) {
            echo " успешно создан.\n";
        } else {
            echo " не был создан.\n";
        }

        // Таблица с шаблонами
        $this->createTable('{{%templates}}', [
            'id'         => $this->primaryKey(),
            'title'      => $this->string(128)->notNull()->unique()->comment('Название'),
            'filename'   => $this->string(64)->notNull()->unique()->comment('Имя файла'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата изменения'),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%templates}}');
        $this->dropTable('{{%users}}');
    }
}
