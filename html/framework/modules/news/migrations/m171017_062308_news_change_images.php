<?php

use yii\db\Migration;
use app\modules\news\models\News;
use yii\imagine\Image;
use yii\helpers\ArrayHelper;


class m171017_062308_news_change_images extends Migration
{
    public function safeUp()
    {
        return true;
        $dir = Yii::getAlias('@public/news');

        $arrNewSize = [
            '805x410' => [
                'width' => 805,
                'height' => 410,
                'quality' => 100,
                'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND
            ],
            '387x200' => [
                'width' => 387,
                'height' => 200,
                'quality' => 100,
                'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND
            ],
            '403x272' => [
                'width' => 403,
                'height' => 272,
                'quality' => 100,
                'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND
            ],
            '836x401' => [
                'width' => 836,
                'height' => 410,
                'quality' => 100,
                'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND
            ],
        ];

        $arr_images = [];

        foreach (News::find()->where(['not', ['src' => '']])->batch() as $news) {
            foreach ($news as $key => $val) {
                if ($val['src'] != '') {

                    //ADD IMAGES WITH LABEL NOT DELETE
                    $arr_images[] = $dir . '/' . $val['src'];
                    $arr_images[] = $dir . '/thumb-' . $val['src'];

                    //ADD NEW GENERATE IMAGES WITH LABEL NOT DELETE
                    $arr_images = ArrayHelper::merge($arr_images, $this->generateThumb($arrNewSize, $dir, $val['src']));


                }
            }
        }

        //DELETE OLD IMAGES
        foreach (glob($dir . '/*') as $file) {
            if (!in_array($file, $arr_images)) {
                unlink($file);
            }
        }

    }

    private function generateThumb($arrSizes, $dir, $src)
    {

        $arr = [];
        foreach ($arrSizes as $key => $val) {

            $width = $val['width'];
            $height = $val['height'];

            if (!$width || !$height) {
                $image = Image::getImagine()->open($dir . '/' . $src);
                $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();

                if ($width) {
                    $height = ceil($width / $ratio);
                } else {
                    $width = ceil($height * $ratio);
                }
            }

            Image::thumbnail($dir . '/' . $src, $width, $height,
                $val['mode'])->save($dir . '/' . $width . 'x' . $height . '-' . $src,
                ['quality' => $val['quality']]);

            $arr[] = $dir . '/' . $width . 'x' . $height . '-' . $src;

        }

        return $arr;


    }

    public function safeDown()
    {
        //echo "m171017_062308_news_change_images cannot be reverted.\n";

        //return false;
    }
}
