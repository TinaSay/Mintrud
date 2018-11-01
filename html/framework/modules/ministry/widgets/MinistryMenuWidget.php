<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 19.08.17
 * Time: 12:45
 */

namespace app\modules\ministry\widgets;

use app\modules\ministry\models\Ministry;
use yii\base\Widget;

class MinistryMenuWidget extends Widget
{

    /**
     * @var array
     */
    public $options = [];

    /**
     * @return string
     */
    public function run()
    {
        $tree = Ministry::find()->asTree(true);

        $list = [];
        foreach ($tree as $parentFolder) {
            if (isset($parentFolder['selected']) && $parentFolder['selected'] == true && isset($parentFolder['children'])) {
                $list = $parentFolder['children'];
                foreach ($parentFolder['children'] as $folder) {
                    if (isset($folder['selected']) && $folder['selected'] == true && isset($folder['children'])) {
                        foreach ($list as $row) {
                            if ($row['deep_menu'] == Ministry::DEEP_MENU_YES) {
                                unset($row['selected']);
                                array_unshift($folder['children'], $row);
                            }
                        }
                        $list = $folder['children'];
                        foreach ($folder['children'] as $article) {
                            if (isset($article['selected']) && $article['selected'] == true && isset($article['children'])) {
                                foreach ($list as $row) {
                                    if ($row['deep_menu'] == Ministry::DEEP_MENU_YES) {
                                        unset($row['selected']);
                                        array_unshift($article['children'], $row);
                                    }
                                }
                                $list = $article['children'];
                                foreach ($article['children'] as $item) {
                                    if (isset($item['selected']) && $item['selected'] == true && isset($item['children'])) {
                                        foreach ($list as $row) {
                                            if ($row['deep_menu'] == Ministry::DEEP_MENU_YES) {
                                                unset($row['selected']);
                                                array_unshift($item['children'], $row);
                                            }
                                        }
                                        $list = $item['children'];
                                        break;
                                    }
                                }

                                break;
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }


        return $this->render('right-menu', ['list' => $list]);
    }
}