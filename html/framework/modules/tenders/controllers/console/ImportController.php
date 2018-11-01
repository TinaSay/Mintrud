<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 07.09.17
 * Time: 9:43
 */

namespace app\modules\tenders\controllers\console;

use app\modules\tenders\models\Tender;
use Yii;
use yii\console\Controller;
use yii\db\Expression;
use yii\httpclient\Client;

class ImportController extends Controller
{
    const BASE_URL = 'http://zakupki.gov.ru';
    const LIST_URL = '/epz/order/extendedsearch/results.html?searchString=&morphology=on&openMode=USE_DEFAULT_PARAMS&sortDirection=false&recordsPerPage=_50&showLotsInfoHidden=false&fz44=on&orderNumber=&placingWaysList=&placingWaysList223=&priceFrom=&priceTo=&currencyId=1&participantName=&publishDateFrom=&publishDateTo=&updateDateFrom=&updateDateTo=&customerTitle=&customerCode=&customerFz94id=&customerFz2';

    /**
     * @var int
     */
    protected $queryInterval = 200000;// microseconds

    /**
     * @var bool
     */
    public $clear = false;

    public function options($actionID)
    {
        return ['clear'];
    }

    /**
     * @return int
     */
    public function actionIndex()
    {
        $page = 1;
        $parse_ok = true;

        if ($this->clear) {
            Yii::$app->getDb()->createCommand('TRUNCATE ' . Tender::tableName())->execute();
        }

        $xpathItem = '.mainBox .registerBox';
        $xpathItemAuction = '.tenderTd > dl > dt';
        $xpathItemStatus = '.tenderTd .noWrap';
        $xpathItemOrderSum = '.tenderTd > dl dd strong';
        $xpathItemRegNumber = '.descriptTenderTd > dl > dt > a';
        $xpathItemTitle = '.descriptTenderTd > dl dd:eq(1)';
        $xpathItemOrderIdentity = '.descriptTenderTd > dl .greyText:eq(0)';
        $xpathItemCreatedAt = '.amountTenderTd > ul li:eq(0)';
        $xpathItemUpdatedAt = '.amountTenderTd > ul li:eq(1)';

        $xpathLastPage = '.paging .rightArrow a';
        $maxPage = false;

        $url_parts = parse_url(self::BASE_URL . self::LIST_URL);
        while ($parse_ok) {
            $parse_ok = false;

            $url = $url_parts['scheme'] . "://" . $url_parts['host'] . $url_parts['path'] .
                "?" . (isset($url_parts['query']) ? $url_parts['query'] . '&' : '') . '&pageNumber=' . $page;

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('get')
                ->setHeaders([
                    'Host' => 'zakupki.gov.ru',
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36',

                ])
                ->setUrl($url)
                ->send();

            if ($response->isOk) {
                $content = $response->content;
                $content = preg_replace('#<script[^>]*?>.*?</script>#si', '', $content);
                $content = trim($content);
                $document = \phpQuery::newDocument($content);

                if ($maxPage === false) {
                    $lastPageNode = $document->find($xpathLastPage)->eq(0);
                    if ($lastPageNode->length > 0) {
                        $maxPage = $lastPageNode->attr('href');
                        $maxPage = preg_replace('#([^\d]+)#', '', $maxPage);
                        $maxPage = (int)$maxPage;
                    }
                }
                if ($maxPage <= 0) {
                    $maxPage = 1;
                }

                $nodeList = $document->find($xpathItem);
                if ($nodeList->length > 0) {
                    $imported = 0;
                    foreach ($nodeList as $nodeItem) {
                        $regNumber = pq($nodeItem)->find($xpathItemRegNumber)->eq(0);
                        if ($regNumber->length <= 0) {
                            continue;
                        }
                        $regNumber = trim($regNumber->text());
                        $regNumber = preg_replace('#([^\d]+)#i', '', $regNumber);
                        $item = Tender::findOne(['regNumber' => $regNumber]);
                        if ($item) {
                            continue;
                        }
                        $imported++;
                        $item = new Tender([
                            'regNumber' => $regNumber,
                            'hidden' => Tender::HIDDEN_NO,
                        ]);
                        $item->detachBehavior('TimestampBehavior');

                        $auction = pq($nodeItem)->find($xpathItemAuction)->eq(0);
                        if ($auction->length > 0) {
                            $item->auction = trim(pq($auction)->text());
                        }

                        $statusNode = pq($nodeItem)->find($xpathItemStatus)->eq(0);
                        if ($statusNode->length > 0) {
                            $status = pq($statusNode)->text();
                            $status = preg_replace('#\/?([\s]*)44-ФЗ#i', '', $status);
                            $status = trim($status);
                            $status_id = array_search($status, Tender::getStatusLIst());
                            if ($status_id === false) {
                                $status_id = Tender::STATUS_SUBMISSION;
                            }
                            $item->status = $status_id;
                        } else {
                            $item->status = Tender::STATUS_SUBMISSION;
                        }

                        $titleNode = pq($nodeItem)->find($xpathItemTitle)->eq(0);
                        if ($titleNode->length > 0) {
                            $item->title = trim(pq($titleNode)->text());
                        } else {
                            $item->title = $item->regNumber;
                        }

                        $orderSumNode = pq($nodeItem)->find($xpathItemOrderSum)->eq(0);
                        if ($orderSumNode->length > 0) {
                            $orderSum = trim(pq($orderSumNode)->text());
                            $orderSum = preg_replace('#([^\d\,\.]+)#', '', $orderSum);
                            $orderSum = preg_replace('#,#', '.', $orderSum);
                            $item->orderSum = (float)$orderSum;
                        }

                        $orderIdentityNode = pq($nodeItem)->find($xpathItemOrderIdentity)->eq(0);
                        if ($orderIdentityNode->length > 0) {
                            if (pq($orderIdentityNode)->find('.multiIkz > p')->length > 0) {
                                $item->orderIdentity = trim(pq($orderIdentityNode)->find('.multiIkz > p')->eq(0)->text());
                            } else {
                                $l = array_map('trim', explode(PHP_EOL, trim(pq($orderIdentityNode)->text())));
                                $l = array_unique(array_diff($l, ['']));
                                $item->orderIdentity = array_pop($l);
                            }
                        }

                        $createdAtNode = pq($nodeItem)->find($xpathItemCreatedAt)->eq(0);
                        if ($createdAtNode->length > 0) {
                            $createdAt = trim(pq($createdAtNode)->text());
                            $createdAt = preg_replace('#([^\d\.]+)#', '', $createdAt);
                            if (($createdAt = \DateTime::createFromFormat('d.m.Y', $createdAt)) !== false) {
                                $item->createdAt = $createdAt->format('Y-m-d');
                            }
                        }
                        if (!$item->createdAt) {
                            $item->createdAt = new Expression('NOW()');
                        }

                        $updatedAtNode = pq($nodeItem)->find($xpathItemUpdatedAt)->eq(0);
                        if ($updatedAtNode->length > 0) {
                            $updatedAt = trim(pq($updatedAtNode)->text());
                            $updatedAt = preg_replace('#([^\d\.]+)#', '', $updatedAt);
                            if (($updatedAt = \DateTime::createFromFormat('d.m.Y', $updatedAt)) !== false) {
                                $item->updatedAt = $updatedAt->format('Y-m-d');
                            }
                        }
                        if (!$item->updatedAt) {
                            $item->updatedAt = new Expression('NOW()');
                        }

                        $item->save();
                        if ($item->hasErrors()) {
                            print_r($item->getAttributes());
                            print_r($item->getErrors());
                            exit;
                        }
                    }
                    print date('Y-m-d H:i:s') . PHP_EOL;
                    print 'Items total: ' . $nodeList->length . '; imported: ' . $imported . PHP_EOL;
                    $parse_ok = true;
                }
                $page++;
                if ($page > $maxPage) {
                    $parse_ok = false;
                }
                usleep($this->queryInterval);
            } else {
                print $response->getStatusCode() . ': ' . $response->getContent() . PHP_EOL;
            }
        }

        return self::EXIT_CODE_NORMAL;
    }
}