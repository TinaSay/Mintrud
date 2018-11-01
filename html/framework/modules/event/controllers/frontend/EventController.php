<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:49
 */
declare(strict_types=1);


namespace app\modules\event\controllers\frontend;

use app\modules\event\forms\AccreditationForm;
use app\modules\event\models\Accreditation;
use app\modules\event\models\repositrories\EventRepository;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;
use yii\data\Pagination;
use yii\web\Response;

/**
 * Class NewsController
 * @package app\modules\news\controllers\frontend
 */
class EventController extends Controller
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        $id,
        Module $module,
        EventRepository $eventRepository,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->eventRepository = $eventRepository;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'cmf2' : null,
                'testLimit' => 0,
            ],
        ];
    }


    /**
     * Lists all Event models
     * @return string
     */
    public function actionIndex(): string
    {
        $view = 'index';
        if (Yii::$app->request->get('language') == 'eng') {
            Yii::$app->language = 'en-US';
            $this->layout = '//common-eng';
            $view = 'index-eng';
        }

        $query = $this->eventRepository->queryByLanguageHiddenOrderDate();

        $pagination = new Pagination([
            'totalCount' => $query->count(),
        ]);

        $models = $query
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        return $this->render(
            $view, [
            'models' => $models,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param $id integer
     * @return string
     */
    public function actionView($id): string
    {
        $view = 'view';
        if (Yii::$app->request->get('language') == 'eng') {
            Yii::$app->language = 'en-US';
            $this->layout = '//common-eng';
            $view = 'view-eng';
        }

        $model = $this->eventRepository->findOne((int)$id);
        $this->eventRepository->exceptionNotFoundHttp($model);

        $accreditation = new AccreditationForm();
        $accreditation->event_id = $model->id;

        return $this->render($view, ['model' => $model, 'accreditation' => $accreditation]);
    }

    public function actionSaveAccreditation()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = new AccreditationForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $model = new Accreditation();
            $model->setAttributes($form->getAttributes());
            return ['success' => $model->save()];
        }
        return ['success' => false, 'errors' => $form->getErrors()];
    }
}