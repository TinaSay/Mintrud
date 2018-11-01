<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 15:21
 */

namespace app\modules\cabinet\form;

use app\modules\cabinet\models\Client;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class BlindConfigureForm
 *
 * @package app\modules\cabinet\form
 */
class BlindConfigureForm extends Model
{
    const SCENARIO_FONT_SIZE = 'fontSize';
    const SCENARIO_COLOR_SCHEMA = 'colorSchema';

    /**
     * @var string
     */
    private $fontSize;

    /**
     * @var string
     */
    private $colorSchema;

    /**
     * BlindConfigureForm constructor.
     *
     * @param Client $model
     * @param array $config
     */
    public function __construct(Client $model, array $config = [])
    {
        $this->setFontSize(ArrayHelper::getValue($model->blind, 'fontSize'));
        $this->setColorSchema(ArrayHelper::getValue($model->blind, 'colorSchema'));

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['fontSize'], 'required', 'on' => [self::SCENARIO_FONT_SIZE]],
            [['colorSchema'], 'required', 'on' => [self::SCENARIO_COLOR_SCHEMA]],
            [['fontSize', 'colorSchema'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'fontSize',
            'colorSchema',
        ];
    }

    /**
     * @return string
     */
    public function getFontSize(): ?string
    {
        return $this->fontSize;
    }

    /**
     * @param string $fontSize
     */
    public function setFontSize(?string $fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @return string
     */
    public function getColorSchema(): ?string
    {
        return $this->colorSchema;
    }

    /**
     * @param string $colorSchema
     */
    public function setColorSchema(?string $colorSchema)
    {
        $this->colorSchema = $colorSchema;
    }
}
