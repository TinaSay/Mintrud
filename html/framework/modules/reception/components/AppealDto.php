<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 15:14
 */

namespace app\modules\reception\components;


use app\modules\reception\form\AppealForm;
use yii\web\UploadedFile;

class AppealDto
{
    /**
     * @var AppealForm
     */
    protected $form;

    /**
     * AppealDto constructor.
     * @param AppealForm $form
     */
    public function __construct(AppealForm $form)
    {
        $this->form = $form;
    }

    /**
     * @var array
     */
    protected $attributeAlias = [
        'uid' => 'uid',
        'date' => 'date',
        'ip' => 'ip',
        'agent' => 'agent',
        'lastName' => 'lastname',
        'firstName' => 'firstname',
        'secondName' => 'secondname',
        'email' => 'email',
        'status' => 'status',
        'theme' => 'theme',
        'text' => 'text',
        'reply' => 'reply',
        'city' => 'punkt',
        'cityType' => 'punkt_type',
        'district' => 'rayon',
        'street' => 'street',
        'streetType' => 'street_type',
        'house' => 'dom',
        'block' => 'korpus',
        'flat' => 'flat',
        'region' => 'region',
        'attachments' => 'attachments',
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        $dom = new \DOMDocument("1.0", "utf-8");
        $data = $dom->createElement('data');
        foreach ($this->form->attributes() as $attribute) {
            if (!empty($this->form->{$attribute}) && isset($this->attributeAlias[$attribute])) {
                if ($attribute == 'attachments') {
                    $this->convertAttachments($data, $this->form->{$attribute});
                    continue;
                }
                $element = $dom->createElement(
                    $this->attributeAlias[$attribute],
                    $this->form->{$attribute}
                );

                if (method_exists($this->form, 'get' . $attribute . 'code')) {
                    $element->setAttribute('code', call_user_func([$this->form, 'get' . $attribute . 'code']));
                }
                $data->appendChild($element);
            }
        }
        $dom->appendChild($data);

        return $dom->saveXML();
    }

    /**
     * @param \DOMElement $data
     * @param $attachments
     */
    private function convertAttachments(\DOMElement $data, $attachments)
    {
        $attachmentsNode = $data->ownerDocument->createElement('attachments');
        foreach ($attachments as $attachment) {
            $element = $data->ownerDocument->createElement('file');
            if ($attachment instanceof UploadedFile) {
                $element->setAttribute('filename', $attachment->name);
                $element->nodeValue = base64_encode(file_get_contents($attachment->tempName));
                $attachmentsNode->appendChild($element);
            } elseif (is_string($attachment) && file_exists($attachment)) {
                $element->setAttribute('filename', substr(strrchr($attachment, '/'), 1));
                $element->nodeValue = base64_encode(file_get_contents($attachment));
                $attachmentsNode->appendChild($element);
            }
        }
        $data->appendChild($attachmentsNode);
    }
}