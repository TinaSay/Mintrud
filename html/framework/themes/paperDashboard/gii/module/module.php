<?php
/**
 * This is the template for generating a module class file.
 */

use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?= $ns ?>;

use app\modules\system\components\backend\NameInterface;
use Yii;

/**
* <?= $generator->moduleID ?> module definition class
*/
class <?= $className ?> extends \yii\base\Module implements NameInterface
{
/**
* @inheritdoc
*/
public $controllerNamespace = null;

/**
* @inheritdoc
*/
public function init()
{
parent::init();
}

/**
* @return string
*/
public static function getName()
{
return Yii::t('<?= $generator->messageCategory ?>', <?= $generator->generateString(Inflector::camel2words($generator->moduleID)) ?>);
}
}
