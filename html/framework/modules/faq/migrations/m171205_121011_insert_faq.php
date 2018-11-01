<?php

use app\modules\faq\models\Faq;
use app\modules\faq\models\FaqCategory;
use app\modules\ministry\models\Ministry;
use yii\console\ExitCode;
use yii\db\Migration;

/**
 * Class m171205_121011_insert_faq
 */
class m171205_121011_insert_faq extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $mainPage = Ministry::find()->where([
            'url' => 'reception/help',
        ])->one();
        if (!$mainPage) {
            return ExitCode::OK;
        }

        try {
            $dom = new DOMDocument();
            $fileContent = mb_convert_encoding($mainPage->text, 'HTML-ENTITIES', 'UTF-8');
            $dom->loadHTML($fileContent);

            $xpath = new DOMXPath($dom);

            $categoryElements = $xpath->query('//li[@class="faq-list__category"]/a');
        } catch (Exception $e) {
            return ExitCode::OK;
        }
        if ($categoryElements->length > 0) {
            /** @var DOMElement $element */
            foreach ($categoryElements as $element) {
                $transaction = $this->db->beginTransaction();
                $title = trim($element->textContent);
                $categoryModel = FaqCategory::find()->where([
                    'like',
                    'title',
                    $title,
                    false,
                ])->one();
                if (!$categoryModel) {
                    $categoryModel = new FaqCategory([
                        'title' => $title,
                        'hidden' => FaqCategory::HIDDEN_NO,
                    ]);
                    $categoryModel->detachBehavior('CreatedByBehavior');
                    $categoryModel->save();
                    if ($categoryModel->hasErrors()) {
                        print_r($categoryModel->getErrors());
                    }
                }
                $questionElements = $xpath->query('../ul/li/a', $element);

                if ($questionElements->length > 0) {
                    /** @var DOMElement $questionNode */
                    foreach ($questionElements as $questionNode) {
                        $title = trim($questionNode->textContent);
                        $url = $questionNode->getAttribute('href');

                        $url = trim($url, '/');

                        $answerPage = Ministry::find()->andWhere([
                            'OR',
                            ['url' => $url],
                            ['url' => '/' . $url],
                        ])->one();
                        if ($answerPage) {
                            try {
                                $domAnswer = new DOMDocument();
                                $fileContent = mb_convert_encoding($answerPage->text, 'HTML-ENTITIES', 'UTF-8');
                                $domAnswer->loadHTML($fileContent);
                            } catch (Exception $e) {
                                continue;
                            }
                            $xpathAnswer = new DOMXPath($domAnswer);

                            $answerNodeList = $xpathAnswer->query('//div[@class="issue"]');
                            if ($answerNodeList->length > 0) {
                                $answerNode = $answerNodeList->item(0);
                            } else {
                                continue;
                            }

                            $faqModel = Faq::find()->where([
                                'like',
                                'question',
                                $title,
                                false,
                            ])->andWhere([
                                'categoryId' => $categoryModel->id,
                            ])->one();
                            if (!$faqModel) {
                                $faqModel = new Faq([
                                    'question' => $title,
                                    'answer' => trim(strip_tags($answerNode->textContent)),
                                    'categoryId' => $categoryModel->id,
                                    'hidden' => Faq::HIDDEN_NO,
                                ]);
                                $faqModel->detachBehavior('CreatedByBehavior');
                                try {
                                    $faqModel->save();
                                } catch (Exception $e) {
                                    print_r($faqModel->getErrors());
                                }

                            }
                        }
                    }
                }
                $transaction->commit();
            }
        }

        return ExitCode::OK;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        Faq::deleteAll();
        FaqCategory::deleteAll();
    }

}
