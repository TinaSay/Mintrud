<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:33
 */

namespace app\modules\reception\form;

use app\modules\cabinet\components\VerifyCodeInterface;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\VerifyCode;
use app\modules\reception\models\Appeal;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;

/**
 * Class AppealForm
 *
 * @package app\modules\reception\form
 */
class AppealForm extends Model implements VerifyCodeInterface
{

    const SCENARIO_REGISTRATION = 'registration';
    const FILE_SIZE_LIMIT = 5 * 1024 * 1024;

    /**
     * @var int
     */
    private $uid = 0;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $agent;

    /**
     * @var string
     */
    private $lastName = '';

    /**
     * @var string
     */
    private $firstName = '';

    /**
     * @var string
     */
    private $secondName = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $status = '';

    /**
     * @var string
     */
    private $theme = '';
    /**
     * @var string
     */
    private $text = '';
    /**
     * @var string
     */
    private $reply = Appeal::TYPE_EMAIL;
    /**
     * @var string
     */
    private $city = '';
    /**
     * @var string
     */
    private $cityType = '1';

    /**
     * @var string
     */
    private $index = '';

    /**
     * @var string
     */
    private $district = '';
    /**
     * @var string
     */
    private $street = '';
    /**
     * @var string
     */
    private $streetType = '1';

    /**
     * @var string
     */
    private $house = '';
    /**
     * @var string
     */
    private $block = '';

    /**
     * @var string
     */
    private $flat = '';

    /**
     * @var string
     */
    private $region = '';

    /**
     * @var string
     */
    private $country = '1';

    /**
     * @var UploadedFile[]
     */
    private $attachments = [];

    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @var int
     */
    public $deal;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'lastName',
                    'firstName',
                    'text',
                ],
                'required',
            ],
            [
                [
                    'deal',
                ],
                'required',
                'message' => 'Подтвердите, что ознакомлены с порядком приема обращения',
            ],
            [
                [
                    'email',
                ],
                'required',
                'when' => function (AppealForm $model) {
                    return $model->reply == Appeal::TYPE_EMAIL;
                },
            ],
            [
                'email',
                'exist',
                'skipOnError' => false,
                'targetClass' => Client::className(),
                'when' => function (AppealForm $model) {
                    return $model->reply == Appeal::TYPE_EMAIL;
                },
                'message' => 'Подтвердите E-mail',
            ],
            [
                [
                    'country',
                    'index',
                    'region',
                    'city',
                ],
                'required',
                'when' => function (AppealForm $model) {
                    return $model->getReply() == Appeal::TYPE_POSTAL;
                },
            ],
            [
                [
                    'lastName',
                    'firstName',
                    'secondName',
                    'email',
                    'status',
                    'theme',
                    'reply',
                    'country',
                    'city',
                    'index',
                    'cityType',
                    'district',
                    'street',
                    'streetType',
                    'house',
                    'block',
                    'flat',
                    'region',
                    'deal',
                ],
                'string',
            ],
            [['deal'], 'integer'],
            ['text', 'string', 'max' => 2000],
            [['attachments'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lastName' => 'Фамилия *',
            'firstName' => 'Имя *',
            'secondName' => 'Отчество',
            'theme' => 'Тема обращения',
            'status' => 'Социальное положение',
            // reply type email
            'email' => 'Email',
            // reply type postal
            'country' => 'Страна *',
            'index' => 'Индекс *',
            'region' => 'Регион *',
            'cityType' => 'Тип нас. пункта',
            'city' => 'Населенный пункт *',
            'district' => 'Район',
            'streetType' => 'Тип улицы',
            'street' => 'Наименование улицы',
            'house' => 'Номер дома',
            'block' => 'Корпус/строение',
            'flat' => 'Номер квартиры',
            //
            'text' => 'Текст обращения *',
            'attachments' => 'Документы',
            'deal' => 'С порядком приема и рассмотрения обращений ознакомлен(а)',
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (count($this->attachments) > 0) {
            $size = 0;
            /** @var UploadedFile $attachment */
            foreach ($this->attachments as $attachment) {
                if ($attachment instanceof UploadedFile) {
                    $size += $attachment->size;
                } elseif (is_string($attachment) && file_exists($attachment)) {
                    $size += filesize($attachment);
                }
            }
            if ($size > self::FILE_SIZE_LIMIT) {
                $this->addError('attachments', 'Превышен размер прикрепляемых файлов');
            }
        }

        return parent::beforeValidate();
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'uid',
            'date',
            'ip',
            'agent',
            'lastName',
            'firstName',
            'secondName',
            'email',
            'status',
            'theme',
            'text',
            'reply',
            'city',
            'cityType',
            'district',
            'street',
            'streetType',
            'house',
            'block',
            'flat',
            'region',
            'attachments',
            'deal',
        ];
    }

    /**
     * @return int
     */
    public function getUid(): int
    {
        return (int)$this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date ? $this->date->format(DATE_RFC2822) : (new \DateTime())->format(DATE_RFC2822);
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip ? $this->ip : Yii::$app->request->getUserIP();
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getAgent(): string
    {
        return $this->agent ? $this->agent : Yii::$app->request->getUserAgent();
    }

    /**
     * @param string $agent
     */
    public function setAgent(string $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getSecondName(): string
    {
        return $this->secondName;
    }

    /**
     * @param string $secondName
     */
    public function setSecondName(string $secondName)
    {
        $this->secondName = $secondName;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return ArrayHelper::getValue(self::getSocialStatusAsDropDown(), $this->status, '');
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return ArrayHelper::getValue(self::getThemesAsDropDown(), $this->theme, '');
    }

    /**
     * @return string
     */
    public function getThemeCode(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return HtmlPurifier::process($this->text);
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getReply(): string
    {
        return ArrayHelper::getValue(self::getReplyAsDropDown(), $this->reply, '');
    }

    /**
     * @return string
     */
    public function getReplyCode(): string
    {
        return $this->reply;
    }

    /**
     * @param string $reply
     */
    public function setReply(string $reply)
    {
        $this->reply = $reply;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getCityType(): string
    {
        return $this->getReplyCode() == Appeal::TYPE_POSTAL ? ArrayHelper::getValue(self::getCityTypeAsDropDown(),
            $this->cityType, '') : '';
    }

    /**
     * @return string
     */
    public function getCityTypeCode(): string
    {
        return $this->cityType;
    }

    /**
     * @param string $cityType
     */
    public function setCityType(string $cityType)
    {
        $this->cityType = $cityType;
    }

    /**
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @param string $district
     */
    public function setDistrict(string $district)
    {
        $this->district = $district;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreetType(): string
    {
        return $this->getReplyCode() == Appeal::TYPE_POSTAL ? ArrayHelper::getValue(self::getStreetTypeAsDropDown(),
            $this->streetType, '') : '';
    }

    /**
     * @return string
     */
    public function getStreetTypeCode(): string
    {
        return $this->streetType;
    }

    /**
     * @param string $streetType
     */
    public function setStreetType(string $streetType)
    {
        $this->streetType = $streetType;
    }

    /**
     * @return string
     */
    public function getHouse(): string
    {
        return $this->house;
    }

    /**
     * @param string $house
     */
    public function setHouse(string $house)
    {
        $this->house = $house;
    }

    /**
     * @return string
     */
    public function getBlock(): string
    {
        return $this->block;
    }

    /**
     * @param string $block
     */
    public function setBlock(string $block)
    {
        $this->block = $block;
    }

    /**
     * @return string
     */
    public function getFlat(): string
    {
        return $this->flat;
    }

    /**
     * @param string $flat
     */
    public function setFlat(string $flat)
    {
        $this->flat = $flat;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return ArrayHelper::getValue(self::getStreetTypeAsDropDown(), $this->country . '.' . $this->region, '');
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region)
    {
        $this->region = $region;
    }

    /**
     * @return UploadedFile[]|array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param UploadedFile[] $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @param string $attachment - full path to attached file
     */
    public function addAttachmentAsPath(string $attachment)
    {
        array_push($this->attachments, (string)$attachment);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }


    /**
     * @return array
     */
    public static function getThemesAsDropDown(): array
    {
        return [
            "" => "Общая",
            "labour" => "Трудовые отношения",
            "employment" => "Занятость населения",
            "social" => "Социальная защита",
            "pensions" => "Пенсионное обеспечение",
        ];
    }

    /**
     * @return array
     */
    public static function getSocialStatusAsDropDown(): array
    {
        return [
            "" => "",
            "26" => "ветераны труда",
            "08" => "военнослужащие",
            "12" => "граждане, награжденные знаком «Житель блокадного Ленинграда»",
            "11" => "граждане, подвергшиеся радиационному воздействию",
            "07" => "доноры",
            "15" => "инвалиды ВОВ",
            "22" => "инвалиды (дети инвалиды)",
            "01" => "медицинские работники",
            "24" => "осужденные",
            "28" => "пенсионеры",
            "14" => "прочие",
            "20" => "репрессированные и лица, пострадавшие от репрессий",
            "13" => "труженики тыла",
            "27" => "узники фашистских концлагерей, гетто и др.",
            "19" => "участники ВОВ, ветераны боев.действ.",
            "21" => "участники ликвидации последствий ЧАЭС",
            "09" => "члены семей погибших (умерших) инвалидов, участников ВОВ и ветеранов боевых действий",
        ];
    }

    /**
     * @return array
     */
    public static function getCityTypeAsDropDown(): array
    {
        return [
            "11" => "аул",
            "1" => "г.",
            "7" => "дер.",
            "8" => "пгт.",
            "2" => "пос.",
            "9" => "р.п.",
            "3" => "с.",
            "5" => "ст.",
            "6" => "станица",
            "4" => "хутор",
        ];
    }

    /**
     * @return array
     */
    public static function getStreetTypeAsDropDown(): array
    {
        return [
            "" => "",
            "14" => "а/я",
            "15" => "академгородок",
            "13" => "аллея",
            "2" => "бул.",
            "16" => "в/ч",
            "17" => "вал",
            "18" => "военный городок",
            "11" => "кв-л",
            "10" => "мкрн.",
            "19" => "н.п.",
            "4" => "наб.",
            "20" => "п/о",
            "5" => "пер.",
            "6" => "пл.",
            "8" => "пр.",
            "7" => "просп.",
            "9" => "туп.",
            "1" => "ул.",
            "12" => "шоссе",
        ];
    }

    /**
     * @param $country string
     *
     * @return array
     */
    public static function getRegionAsDropDown(string $country): array
    {
        $regions = [
            // russia
            '1' => [
                '' => '',
                '117' => 'Алтайский край',
                '123' => 'Амурская область',
                '124' => 'Архангельская область',
                '125' => 'Астраханская область',
                '126' => 'Белгородская область',
                '127' => 'Брянская область',
                '128' => 'Владимирская область',
                '129' => 'Волгоградская область',
                '130' => 'Вологодская область',
                '131' => 'Воронежская область',
                '178' => 'Еврейская автономная область',
                '195' => 'Забайкальский край',
                '133' => 'Ивановская область',
                '134' => 'Иркутская область',
                '104' => 'Кабардино-Балкарская Республика',
                '135' => 'Калининградская область',
                '137' => 'Калужская область',
                '196' => 'Камчатский край',
                '179' => 'Карачаево-Черкесская Республика',
                '139' => 'Кемеровская область',
                '140' => 'Кировская область',
                '141' => 'Костромская область',
                '118' => 'Краснодарский край',
                '119' => 'Красноярский край',
                '143' => 'Курганская область',
                '144' => 'Курская область',
                '145' => 'Ленинградская область',
                '146' => 'Липецкая область',
                '147' => 'Магаданская область',
                '173' => 'Москва',
                '148' => 'Московская область',
                '149' => 'Мурманская область',
                '184' => 'Ненецкий автономный округ',
                '132' => 'Нижегородская область',
                '150' => 'Новгородская область',
                '151' => 'Новосибирская область',
                '152' => 'Омская область',
                '153' => 'Оренбургская область',
                '154' => 'Орловская область',
                '155' => 'Пензенская область',
                '191' => 'Пермский край',
                '120' => 'Приморский край',
                '157' => 'Псковская область',
                '176' => 'Республика Адыгея',
                '177' => 'Республика Алтай',
                '101' => 'Республика Башкортостан',
                '102' => 'Республика Бурятия',
                '103' => 'Республика Дагестан',
                '114' => 'Республика Ингушетия',
                '105' => 'Республика Калмыкия',
                '106' => 'Республика Карелия',
                '107' => 'Республика Коми',
                '197' => 'Республика Крым',
                '108' => 'Республика Марий Эл',
                '109' => 'Республика Мордовия',
                '116' => 'Республика Саха (Якутия)',
                '110' => 'Республика Северная Осетия - Алания',
                '111' => 'Республика Татарстан',
                '112' => 'Республика Тыва',
                '180' => 'Республика Хакасия',
                '158' => 'Ростовская область',
                '159' => 'Рязанская область',
                '142' => 'Самарская область',
                '172' => 'Санкт-Петербург',
                '160' => 'Саратовская область',
                '161' => 'Сахалинская область',
                '198' => 'Севастополь',
                '162' => 'Свердловская область',
                '163' => 'Смоленская область',
                '121' => 'Ставропольский край',
                '164' => 'Тамбовская область',
                '136' => 'Тверская область',
                '165' => 'Томская область',
                '166' => 'Тульская область',
                '167' => 'Тюменская область',
                '113' => 'Удмуртская Республика',
                '168' => 'Ульяновская область',
                '122' => 'Хабаровский край',
                '187' => 'Ханты-Мансийский автономный округ',
                '169' => 'Челябинская область',
                '194' => 'Чеченская Республика',
                '115' => 'Чувашская Республика',
                '188' => 'Чукотский автономный округ',
                '190' => 'Ямало-Ненецкий автономный округ',
                '171' => 'Ярославская область',
            ],
            // СНГ и балтия
            '2' => [
                '' => '',
                '15' => 'Абхазия',
                '2' => 'Азербайджан',
                '3' => 'Армения',
                '5' => 'Казахстан',
                '6' => 'Киргизия',
                '7' => 'Латвия',
                '8' => 'Литва',
                '9' => 'Молдова',
                '4' => 'Республика Беларусь',
                '10' => 'Таджикистан',
                '11' => 'Туркменистан',
                '12' => 'Узбекистан',
                '13' => 'Украина',
                '14' => 'Эстония',
                '56' => 'Южная Осетия',
            ],
            // Европа
            '3' => [
                '' => '',
                '16' => 'Австрия',
                '17' => 'Албания',
                '18' => 'Андорра',
                '19' => 'Бельгия',
                '20' => 'Болгария',
                '21' => 'Босния и Герцеговина',
                '22' => 'Ватикан',
                '23' => 'Великобритания',
                '24' => 'Венгрия',
                '25' => 'Германия',
                '26' => 'Гибралтар',
                '27' => 'Греция',
                '28' => 'Грузия',
                '29' => 'Дания',
                '30' => 'Ирландия',
                '31' => 'Исландия',
                '32' => 'Испания',
                '33' => 'Италия',
                '34' => 'Кипр',
                '35' => 'Лихтенштейн',
                '36' => 'Люксембург',
                '37' => 'Македония',
                '38' => 'Мальта',
                '39' => 'Монако',
                '40' => 'Нидерланды',
                '41' => 'Норвегия',
                '42' => 'Польша',
                '43' => 'Португалия',
                '44' => 'Румыния',
                '45' => 'Сан-Марино',
                '46' => 'Сербия',
                '47' => 'Словакия',
                '48' => 'Словения',
                '49' => 'Финляндия',
                '50' => 'Франция',
                '51' => 'Хорватия',
                '52' => 'Черногория',
                '53' => 'Чехия',
                '54' => 'Швейцария',
                '55' => 'Швеция',
            ],
            // страны Америки
            '4' => [
                '' => '',
                '57' => 'Антигуа и Барбуда',
                '58' => 'Антильские острова',
                '59' => 'Аргентина',
                '60' => 'Багамские острова',
                '61' => 'Барбадос',
                '62' => 'Белиз',
                '63' => 'Бермудские острова',
                '64' => 'Боливия',
                '65' => 'Бразилия',
                '66' => 'Венесуэла',
                '67' => 'Виргинские острова',
                '68' => 'Гаити',
                '69' => 'Гайана',
                '70' => 'Гваделупа',
                '71' => 'Гватемала',
                '72' => 'Гвиана',
                '73' => 'Гондурас',
                '74' => 'Гренада',
                '75' => 'Гренландия',
                '76' => 'Доминика',
                '77' => 'Доминиканская Республика',
                '78' => 'Канада',
                '79' => 'Колумбия',
                '80' => 'Коста-Рика',
                '81' => 'Куба',
                '82' => 'Мартиника',
                '83' => 'Мексика',
                '84' => 'Никарагуа',
                '85' => 'Панама',
                '86' => 'Парагвай',
                '87' => 'Перу',
                '88' => 'Пуэрто-Рико',
                '89' => 'Сальвадор',
                '90' => 'Сент-Винсент и Гренадины',
                '91' => 'Сент-Китс и Невис',
                '92' => 'Сент-Люсия',
                '93' => 'Соединенные Штаты Америки',
                '94' => 'Суринам',
                '95' => 'Тринидад и Тобаго',
                '96' => 'Уругвай',
                '97' => 'Фолклендские острова',
                '98' => 'Чили',
                '99' => 'Эквадор',
                '100' => 'Ямайка',
            ],
            // Страны Азии
            '5' => [
                '' => '',
                '101' => 'Афганистан',
                '102' => 'Бангладеш',
                '103' => 'Бахрейн',
                '104' => 'Бруней',
                '105' => 'Бутан',
                '107' => 'Вьетнам',
                '108' => 'Израиль',
                '109' => 'Индия',
                '110' => 'Индонезия',
                '111' => 'Иордания',
                '112' => 'Ирак',
                '113' => 'Иран',
                '114' => 'Йемен',
                '115' => 'Камбоджа',
                '116' => 'Катар',
                '117' => 'Китай',
                '205' => 'Корейская Народно-Демократическая Республика',
                '119' => 'Кувейт',
                '120' => 'Лаос',
                '121' => 'Ливан',
                '122' => 'Малайзия',
                '206' => 'Мальдивы',
                '123' => 'Монголия',
                '131' => 'Мьянма',
                '124' => 'Непал',
                '125' => 'Объединенные Арабские Эмираты',
                '126' => 'Оман',
                '127' => 'Пакистан',
                '211' => 'Палестинская автономия',
                '118' => 'Республика Корея',
                '128' => 'Саудовская Аравия',
                '129' => 'Сингапур',
                '130' => 'Сирия',
                '132' => 'Сянган (Гонконг)',
                '133' => 'Таиланд',
                '134' => 'Тайвань',
                '106' => 'Тимор-Лешти',
                '135' => 'Турция',
                '136' => 'Филиппины',
                '137' => 'Шри-Ланка',
                '138' => 'Япония',
            ],
            // Страны Африки
            '6' => [
                '' => '',
                '139' => 'Алжир',
                '140' => 'Ангола',
                '141' => 'Бенин',
                '142' => 'Ботсвана',
                '143' => 'Буркина-Фасо',
                '144' => 'Бурунди',
                '145' => 'Габон',
                '146' => 'Гамбия',
                '147' => 'Гана',
                '148' => 'Гвинея',
                '149' => 'Гвинея-Бисау',
                '207' => 'Демократическая Республика Конго',
                '150' => 'Джибути',
                '151' => 'Египет',
                '152' => 'Замбия',
                '153' => 'Западная Сахара',
                '154' => 'Зимбабве',
                '155' => 'Кабо-Верде',
                '156' => 'Камерун',
                '157' => 'Кения',
                '158' => 'Коморские острова',
                '159' => 'Конго',
                '160' => 'Кот-д\'Ивуар',
                '161' => 'Лесото',
                '162' => 'Либерия',
                '163' => 'Ливия',
                '164' => 'Маврикий',
                '165' => 'Мавритания',
                '166' => 'Мадагаскар',
                '167' => 'Малави',
                '168' => 'Мали',
                '169' => 'Марокко',
                '170' => 'Мозамбик',
                '204' => 'Намибия',
                '171' => 'Нигер',
                '172' => 'Нигерия',
                '173' => 'Реюньон',
                '174' => 'Руанда',
                '175' => 'Сан-Томе и Принсипи',
                '176' => 'Свазиленд',
                '177' => 'Сейшельские острова',
                '178' => 'Сенегал',
                '179' => 'Сомали',
                '180' => 'Судан',
                '181' => 'Сьерра-Леоне',
                '182' => 'Танзания',
                '183' => 'Того',
                '184' => 'Тунис',
                '185' => 'Уганда',
                '186' => 'Центральноафриканская Республика',
                '187' => 'Чад',
                '188' => 'Экваториальная Гвинея',
                '208' => 'Эритрея',
                '189' => 'Эфиопия',
                '190' => 'Южно-Африканская Республика',
            ],
            // Страны Австралии и Океании
            '7' => [
                '' => '',
                '191' => 'Австралия',
                '192' => 'Вануату',
                '193' => 'Гуам',
                '195' => 'Кирибати',
                '209' => 'Маршалловы Острова',
                '196' => 'Микронезия',
                '197' => 'Науру',
                '198' => 'Новая Зеландия',
                '210' => 'Палау',
                '199' => 'Папуа-Новая Гвинея',
                '194' => 'Самоа',
                '200' => 'Соломоновы острова',
                '201' => 'Тонга',
                '202' => 'Тувалу',
                '203' => 'Фиджи',
            ],
        ];

        return ArrayHelper::getValue($regions, $country, []);
    }

    /**
     * @return array
     */
    public static function getReplyAsDropDown(): array
    {
        return [
            'email' => 'Электронная почта',
            'mail' => 'Почтовое отправление',
        ];
    }

    /**
     * @return array
     */
    public static function getCountryAsDropDown(): array
    {
        return [
            '1' => 'Россия',
            '2' => 'Страны СНГ и Балтии',
            '3' => 'Страны Европы',
            '4' => 'Страны Америки',
            '5' => 'Страны Азии',
            '6' => 'Страны Африки',
            '7' => 'Страны Австралии и Океании',
        ];
    }

    /**
     * @return bool
     */
    public function existsEmailInVerify(): bool
    {
        if (empty($this->email)) {
            return false;
        }

        return VerifyCode::find()->where([
            'like',
            'attribute',
            $this->getEmail(),
            false,
        ])->exists();
    }
}