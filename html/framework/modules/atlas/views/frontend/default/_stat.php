<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.07.17
 * Time: 11:01
 */

/** @var $this yii\web\View */
/** @var $region \app\modules\atlas\models\AtlasDirectorySubjectRf */
/** @var $list array */
/** @var $stat_year array */
/** @var $stat_prev_year array */
/** @var $stat_diff_year array */
/** @var $total array */

/** @var $year int */

/** @var $reg_id int */

use app\modules\atlas\models\AtlasDirectoryRate;
use yii\helpers\ArrayHelper;

?>

<div class="col-xs-12">
    <h2 class="text-black story-top-title"><?= $region['title']; ?></h2>
</div>

<div data-reg_id="<?= $reg_id; ?>" class="col-md-8 stat digit-box-2">
    <?php if ($total): ?>
        <div class="point">
            <h3>Общая численность населения</h3>
            <div><?= number_format((float)$total['value'], 0, ',', ' ') ?> на 1 января <?= $total['year']; ?> г.</div>
        </div>
    <?php endif; ?>
    <?php foreach ($list as $item): ?>
        <?php if (isset($item['children']) && isset($stat_year[current($item['children'])['id']])): ?>
            <div class="point">
                
                <h3><?= $item['title'] ?></h3>
                <table>
                    <tr class="year">
                        <?php if (isset($item['children'])): ?>
                            <th>&nbsp;</th><?php endif; ?>
                        <?php if ($stat_prev_year): ?>
                            <th><?= $year - 1; ?> г.</th><?php endif; ?>
                        <th><?= $year; ?> г.</th>
                        <?php if ($item['stat_type'] == AtlasDirectoryRate::STAT_TYPE_YEAR_DIFF && $stat_diff_year): ?>
                            <th><?= $year; ?> г. к <?= $year - 1; ?> г., %</th><?php endif; ?>
                    </tr>
                    <?php if (isset($item['children'])): ?>
                        <?php foreach ($item['children'] as $child): ?>
                            <tr class="value">
                                <th width="350" class="title"><?= $child['title']; ?></th>
                                <?php if ($stat_prev_year): ?>
                                <th>
                                    <?= ArrayHelper::getValue($stat_prev_year, $child['id'], 0); ?>
                                    <?php endif; ?>
                                <th><?= ArrayHelper::getValue($stat_year, $child['id'], 0); ?>
                                </th>
                                <?php if ($item['stat_type'] == AtlasDirectoryRate::STAT_TYPE_YEAR_DIFF && $stat_diff_year): ?>
                                    <th><?= ArrayHelper::getValue($stat_diff_year, $child['id'], 0); ?></th>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
