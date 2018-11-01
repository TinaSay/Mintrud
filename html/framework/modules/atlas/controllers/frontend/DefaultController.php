<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:49
 */
declare(strict_types=1);


namespace app\modules\atlas\controllers\frontend;

use app\modules\atlas\models\AtlasDirectory;
use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\atlas\models\AtlasDirectorySubjectRf;
use app\modules\atlas\models\AtlasDirectoryYear;
use app\modules\atlas\models\AtlasStat;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class DefaultController
 *
 * @package app\modules\atlas\controllers\frontend
 */
class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $rates = AtlasDirectoryRate::getTree(AtlasDirectoryRate::HIDDEN_NO);


        return $this->render('index', [
            'rates' => $rates,
        ]);
    }

    /**
     * @param $code
     *
     * @return array
     */
    public function actionGetLayer($code)
    {
        $rate_code = Yii::$app->request->get('rate', '');

        $key = [
            __CLASS__,
            __METHOD__,
            $code,
            $rate_code,
        ];

        $dependency = new TagDependency([
            'tags' => [
                AtlasStat::class,
                AtlasDirectoryRate::class,
                AtlasDirectorySubjectRf::class,
                AtlasDirectoryYear::class,
            ],
        ]);

        if (!($list = Yii::$app->cache->get($key))) {

            $rate = AtlasDirectoryRate::find()->where([
                AtlasDirectory::tableName() . '.[[code]]' => $code,
            ])->joinWith('children')->one();

            $subjects = AtlasDirectorySubjectRf::find()->select(['code', 'id'])->indexBy('id')->column();
            if ($rate && $rate->children) {
                /*if (!$year) {
                    $directory_rate_id = current($rate->children)->id;
                    $year = AtlasStat::find()->where(['directory_rate_id' => $directory_rate_id])->max('year');
                }*/
                foreach ($rate->children as $child) {
                    $stat = AtlasStat::find()->where([
                        'directory_rate_id' => $child->id,
                    ])->orderBy([
                        'year' => SORT_ASC,
                    ])->asArray()->all();

                    if ($stat) {
                        foreach ($stat as $stat_row) {
                            $subject_code = ArrayHelper::getValue($subjects, $stat_row['directory_subject_id']);
                            $rate_title = preg_replace('#' . $rate->title . ',?\s?#i', '', $child->title);
                            $rate_title = trim($rate_title);
                            if (!isset($list[$stat_row['year']])) {
                                $list[$stat_row['year']] = [
                                    $subject_code => [
                                        $rate_title => $stat_row['value'],
                                    ],
                                ];
                                if ($child['value'] === AtlasDirectoryRate::VALUE_YES &&
                                    empty($rate_code)) {
                                    $list[$stat_row['year']][$subject_code]['value'] = $stat_row['value'];
                                } elseif ($child['value'] === AtlasDirectoryRate::VALUE_YES && $rate_code == $child['code']) {
                                    $list[$stat_row['year']][$subject_code]['value'] = $stat_row['value'];
                                }
                            } else {
                                if (!isset($list[$stat_row['year']][$subject_code])) {
                                    $list[$stat_row['year']][$subject_code] = [];
                                }
                                $list[$stat_row['year']][$subject_code][$rate_title] = $stat_row['value'];

                                $prev_year = $stat_row['year'] - 1;
                                if ($rate->stat_type === AtlasDirectoryRate::STAT_TYPE_YEAR_DIFF &&
                                    isset($list[$prev_year][$subject_code]) &&
                                    $list[$stat_row['year']][$subject_code][$rate_title] > 0
                                ) {
                                    $currentYearRate = isset($list[$stat_row['year']][$subject_code][$rate_title]) ?: 1;
                                    $list[$stat_row['year']][$subject_code]['diff'][$rate_title] =
                                        round($list[$prev_year][$subject_code][$rate_title] / $currentYearRate * 100,
                                            2);
                                }

                                if ($child['value'] === AtlasDirectoryRate::VALUE_YES &&
                                    empty($rate_code) &&
                                    !isset($list[$stat_row['year']][$subject_code]['value'])
                                ) {
                                    $list[$stat_row['year']][$subject_code]['value'] = $stat_row['value'];
                                } elseif ($child['value'] === AtlasDirectoryRate::VALUE_YES && $rate_code == $child['code']) {
                                    $list[$stat_row['year']][$subject_code]['value'] = $stat_row['value'];
                                }
                            }
                        }
                    }
                }
            }
            Yii::$app->cache->set($key, $list, null, $dependency);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['success' => (count($list) > 0), 'list' => $list];
    }

    /**
     * @param $reg int
     * @param $year int
     *
     * @return string
     */
    public function actionGetRegionData($reg, $year)
    {

        $year = (int)$year;
        $key = [
            __CLASS__,
            __METHOD__,
            $reg,
            $year,
        ];

        $dependency = new TagDependency([
            'tags' => [
                AtlasStat::class,
                AtlasDirectoryRate::class,
                AtlasDirectorySubjectRf::class,
                AtlasDirectoryYear::class,
            ],
        ]);

        if (!($data = Yii::$app->cache->get($key))) {
            $region = AtlasDirectorySubjectRf::findOne([
                'code' => $reg,
                'type' => AtlasDirectorySubjectRf::getType(),
            ]);

            $list = AtlasDirectoryRate::getTree(AtlasDirectoryRate::HIDDEN_NO);

            $query = AtlasStat::find()->select([
                AtlasStat::tableName() . '.[[value]]',
                AtlasStat::tableName() . '.[[directory_subject_id]]',
                AtlasStat::tableName() . '.[[directory_rate_id]]',
            ])->where(
                [AtlasStat::tableName() . '.[[year]]' => $year]
            )->joinWith('directorySubject', false)
                ->onCondition(['[[subject]].[[code]]' => $reg])
                ->orderBy([
                    AtlasStat::tableName() . '.[[year]]' => SORT_ASC,
                ])->indexBy('directory_rate_id');

            $stat_year = $query->asArray()->column();

            $query->where(
                [AtlasStat::tableName() . '.[[year]]' => $year - 1]
            );
            $stat_prev_year = $query->asArray()->column();

            $stat_diff_year = [];

            $total = AtlasStat::find()->select([
                AtlasStat::tableName() . '.[[directory_subject_id]]',
                AtlasStat::tableName() . '.[[directory_rate_id]]',
                AtlasStat::tableName() . '.[[year]]',
                AtlasStat::tableName() . '.[[value]]',
                '[[rate]].[[title]]',
            ])->joinWith('directoryRate', false, 'INNER JOIN')
                ->where(['[[rate]].[[code]]' => 'total'])
                ->joinWith('directorySubject', false, 'INNER JOIN')
                ->andWhere(['[[subject]].[[code]]' => $reg])
                ->orderBy([
                    AtlasStat::tableName() . '.[[year]]' => SORT_DESC,
                ])->limit(1)->asArray()->one();

            if ($stat_year) {
                foreach ($stat_year as $directory_rate_id => $value) {

                    /** @var $rate AtlasDirectoryRate */
                    if ($stat_prev_year &&
                        isset($stat_prev_year[$directory_rate_id]) &&
                        $stat_prev_year[$directory_rate_id] > 0
                    ) {
                        $stat_diff_year[$directory_rate_id] = $this->format_number($value / $stat_prev_year[$directory_rate_id] * 100);
                    }

                    $stat_year[$directory_rate_id] = $this->format_number($value);

                    if (isset($stat_prev_year[$directory_rate_id])) {
                        $stat_prev_year[$directory_rate_id] = $this->format_number($stat_prev_year[$directory_rate_id]);
                    }
                }
            }
            $data = [
                'list' => $list,
                'region' => $region,
                'stat_year' => $stat_year,
                'stat_prev_year' => $stat_prev_year,
                'stat_diff_year' => $stat_diff_year,
                'total' => $total,
            ];
            Yii::$app->cache->set($key, $data, null, $dependency);
        }

        return $this->renderAjax('_stat', $data + [
                'year' => $year,
                'reg_id' => $reg,
            ]);
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function format_number($value)
    {
        $value = (float)$value;
        $precision = 0;
        if (preg_match('#\.|\,#', (string)$value)) {
            $precision = 1;
        }

        return number_format($value, $precision, ',', ' ');
    }
}