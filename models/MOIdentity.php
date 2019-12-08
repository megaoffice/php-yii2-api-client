<?php


namespace megaoffice\client\models;


use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class MOIdentity extends MOClients implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    //public $options = [];
    public $phones  = [];
    public $emails  = [];

    public $username;
    public $regStatus;
    public $password_hash;
    public $auth_key;
    public $password_reset_token;
    public $verification_token;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['type', 'safe'],
            ['status', 'safe'],
            ['options', 'safe'],
            ['emails', 'safe'],
            ['email', 'safe'],
            ['phones', 'safe'],
            ['phone', 'safe'],
            ['password_hash', 'safe'],
            ['auth_key', 'safe'],
            ['password_rewset_token', 'safe'],
            ['verification_token', 'safe'],
            ['username', 'string'],
            ['regStatus', 'default', 'value' => self::STATUS_INACTIVE],
            ['regStatus', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function beforeSave($insert)
    {

        if(isset($this->password_hash) ||
            isset($this->username) ||
            isset($this->regStatus) ||
            isset($this->auth_key) ||
            isset($this->password_reset_token) ||
            isset($this->verification_token)){
            $identity = [
                'username'              => $this->username,
                'status'                => $this->regStatus,
                'password_hash'         => $this->password_hash,
                'auth_key'              => $this->auth_key,
                'password_reset_token'  => $this->password_reset_token,
                'verification_token'    => $this->verification_token,
            ];

            $options = $this->options;
            $options['identity'] = $identity;
            $this->options = $options;

        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'options.idenitity.status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['option.identity.username' => $username, 'option.identity.status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'option.identity.password_reset_token' => $token,
            'option.identity.status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'option.identity.verification_token' => $token,
            'option.identity.status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->options['identity']['auth_key'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


}