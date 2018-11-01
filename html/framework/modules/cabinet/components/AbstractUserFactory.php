<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 23.06.17
 * Time: 15:07
 */

namespace app\modules\cabinet\components;

use app\modules\cabinet\form\BlindConfigureForm;
use app\modules\cabinet\form\ChangePasswordForm;
use app\modules\cabinet\form\CreateForm;
use app\modules\cabinet\form\DeleteForm;
use app\modules\cabinet\form\LoginWithEmailForm;
use app\modules\cabinet\form\OAuthForm;
use app\modules\cabinet\form\RegistrationWithVerifyForm;
use app\modules\cabinet\form\ResetWithVerifyForm;
use app\modules\cabinet\form\UpdateForm;
use app\modules\cabinet\form\VerifyCodeForm;
use app\modules\cabinet\form\ViewForm;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\ClientSearch;
use app\modules\cabinet\models\Log;
use app\modules\cabinet\models\LogSearch;
use app\modules\cabinet\models\OAuth;
use app\modules\cabinet\models\VerifyCode;
use app\modules\cabinet\services\BlindApplyService;
use app\modules\cabinet\services\BlindConfigureService;
use app\modules\cabinet\services\ChangePasswordService;
use app\modules\cabinet\services\LoginWithEmailService;
use app\modules\cabinet\services\OAuthService;
use app\modules\cabinet\services\RegistrationWithVerifyService;
use app\modules\cabinet\services\ResetWithVerifyService;
use app\modules\cabinet\services\Service;
use app\modules\cabinet\services\VerifyCodeService;
use Yii;
use yii\base\UnknownClassException;

/**
 * Class AbstractUserFactory
 *
 * @package app\modules\cabinet\components
 */
abstract class AbstractUserFactory
{
    /**
     * @param string $class
     * @param array $configuration
     *
     * @return object
     * @throws UnknownClassException
     */
    public function service($class, array $configuration = [])
    {
        switch ($class) {
            case 'Service':
                return Yii::createObject(array_merge(['class' => Service::class], $configuration));
                break;
            case 'RegistrationWithVerify':
                return Yii::createObject(array_merge(['class' => RegistrationWithVerifyService::class],
                    $configuration));
                break;
            case 'OAuth':
                return Yii::createObject(OAuthService::class, $configuration);
                break;
            case 'VerifyCode':
                return Yii::createObject(array_merge(['class' => VerifyCodeService::class],
                    $configuration));
                break;
            case 'ResetWithVerify':
                return Yii::createObject(array_merge(['class' => ResetWithVerifyService::class],
                    $configuration));
                break;
            case 'LoginWithEmail':
                return Yii::createObject(array_merge(['class' => LoginWithEmailService::class], $configuration));
                break;
            case 'ChangePassword':
                return Yii::createObject(array_merge(['class' => ChangePasswordService::class], $configuration));
                break;
            case 'BlindConfigure':
                return Yii::createObject(['class' => BlindConfigureService::class], $configuration);
                break;
            case 'BlindApply':
                return Yii::createObject(['class' => BlindApplyService::class], $configuration);
                break;
            default:
                throw new UnknownClassException();
        }
    }

    /**
     * @param string $class
     * @param array $configuration
     *
     * @return object
     * @throws UnknownClassException
     */
    public function form($class, array $configuration = [])
    {
        switch ($class) {
            case 'RegistrationWithVerify':
                return Yii::createObject(array_merge(['class' => RegistrationWithVerifyForm::class], $configuration));
                break;
            case 'LoginWithEmail':
                return Yii::createObject(array_merge(['class' => LoginWithEmailForm::class], $configuration));
                break;
            case 'VerifyCode':
                return Yii::createObject(array_merge(['class' => VerifyCodeForm::class], $configuration));
                break;
            case 'ResetWithVerify':
                return Yii::createObject(array_merge(['class' => ResetWithVerifyForm::class], $configuration));
                break;
            case 'ChangePassword':
                return Yii::createObject(array_merge(['class' => ChangePasswordForm::class], $configuration));
                break;
            case 'BlindConfigure':
                return Yii::createObject(['class' => BlindConfigureForm::class], $configuration);
                break;
            case 'OAuth':
                return Yii::createObject(array_merge(['class' => OAuthForm::class], $configuration));
                break;
            case 'View':
                return Yii::createObject(array_merge(['class' => ViewForm::class], $configuration));
                break;
            case 'Create':
                return Yii::createObject(array_merge(['class' => CreateForm::class], $configuration));
                break;
            case 'Update':
                return Yii::createObject(array_merge(['class' => UpdateForm::class], $configuration));
                break;
            case 'Delete':
                return Yii::createObject(array_merge(['class' => DeleteForm::class], $configuration));
                break;
            default:
                throw new UnknownClassException();
        }
    }

    /**
     * @param string $class
     * @param array $configuration
     *
     * @return object
     * @throws UnknownClassException
     */
    public function model($class, array $configuration = [])
    {
        switch ($class) {
            case 'Client':
                return Yii::createObject(array_merge(['class' => Client::class], $configuration));
                break;
            case 'ClientSearch':
                return Yii::createObject(array_merge(['class' => ClientSearch::class], $configuration));
                break;
            case 'Log':
                return Yii::createObject(array_merge(['class' => Log::class], $configuration));
                break;
            case 'LogSearch':
                return Yii::createObject(array_merge(['class' => LogSearch::class], $configuration));
                break;
            case 'VerifyCode':
                return Yii::createObject(array_merge(['class' => VerifyCode::class], $configuration));
                break;
            case 'OAuth':
                return Yii::createObject(array_merge(['class' => OAuth::class], $configuration));
                break;
            default:
                throw new UnknownClassException();
        }
    }
}
