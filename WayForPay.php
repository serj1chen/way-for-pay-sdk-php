<?php


/**
 * Принять платеж (Purchase)
 *
 * Пример:
 *      $order = new WayForPay1("test_merch_n1", "flk3409refn54t54t*FNJRET");
 *      $order->addProduct("Процессор Intel Core i5-4670 3.4GHz",1000, 1)
 *          ->addProduct("Память Kingston DDR3-1600 4096MB PC3-12800", 547.36, 1)
 *          ->setMerchantDomainName('www.market.ua')
 *          ->setOrderReference('55')
 *          ->setOrderDate(1415379863)
 *          ->setAmount(1547.36)
 *          ->setCurrency('UAH');
 *
 *      echo $order->getButtonPayment('Отправить', array('class'=>'paymentOrder', 'id'=>'btnPayment'));
 *
 * Class WayForPay
 * @author Chenakal Serhii
 */
class WayForPay
{
    const ADDRESS_URL_WAY_FOR_PAY = "https://secure.wayforpay.com/pay";

    /** @var  string Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay */
    private $merchantAccount;
    /** @var  string Тип авторизации. */
    private $merchantAuthType = 'SimpleSignature';
    /** @var  string Доменное имя веб-сайта торговца */
    private $merchantDomainName;
    /** @var  string Тип транзакции. */
    private $merchantTransactionType = 'AUTO';
    /** @var  string Тип безопасности для прохождение транзакции. */
    private $merchantTransactionSecureType = 'AUTO';
    /** @var  string Язык платежной страницы. */
    private $language = 'RU';
    /** @var  string URL, на который система должна перенаправлять клиента с результатом платежа. */
    private $returnUrl;
    /** @var  string URL, на который система должна отправлять ответ с результатом платежа напрямую мерчанту */
    private $serviceUrl;
    /** @var  string Уникальный номер заказа в системе торговца */
    private $orderReference;
    /** @var  int Дата размещение заказа */
    private $orderDate;
    /** @var  float Сумма заказа */
    private $amount;
    /** @var  string Валюта заказа UAH */
    private $currency = 'UAH';
    /** @var  float Альтернативная сумма заказа */
    private $alternativeAmount;
    /** @var  string Альтернативная валюта заказа */
    private $alternativeCurrency;
    /** @var  int Устанавливает интервал, в течении которого заказ может быть оплачен. В секундах */
    private $orderTimeout;
    /** @var  string Токен карты для рекаренговых списаний */
    private $recToken;
    /** @var  array Массив с наименованием заказанных товаров */
    private $productName = array();
    /** @var  array Массив с ценами за единицу товара. Данная информация будет видна на странице оплаты заказа */
    private $productPrice = array();
    /** @var  array Массив с количеством заказанного товара по каждой позиции. */
    private $productCount = array();
    /** @var  string уникальный идентификатор клиента в системе торговца (логин, email и т.д.) */
    private $clientAccountId;
    /** @var  string Уникальный идентификатор ресурса. */
    private $socialUri;
    /** @var  string Имя клиента */
    private $clientFirstName;
    /** @var  string Фамилия клиента */
    private $clientLastName;
    /** @var  string Адрес клиента */
    private $clientAddress;
    /** @var  string Город клиента */
    private $clientCity;
    /** @var  string Штат/Область клиента */
    private $clientState;
    /** @var  string Почтовый индекс клиента */
    private $clientZipCode;
    /** @var  string Страна клиента в цифровом ISO 3166-1-Alpha 3 */
    private $clientCountry;
    /** @var  string Email клиента */
    private $clientEmail;
    /** @var  string Номер телефона клиента */
    private $clientPhone;
    /** @var  string Имя получателя */
    private $deliveryFirstName;
    /** @var  string Фамилия получателя */
    private $deliveryLastName;
    /** @var  string Адрес получателя */
    private $deliveryAddress;
    /** @var  string Город получателя */
    private $deliveryCity;
    /** @var  string Штат/Область получателя */
    private $deliveryState;
    /** @var  string Почтовый индекс получателя */
    private $deliveryZipCode;
    /** @var  string Страна получателя */
    private $deliveryCountry;
    /** @var  string Email получателя */
    private $deliveryEmail;
    /** @var  string Номер телефона получателя */
    private $deliveryPhone;
    /** @var  string Время отправления рейса */
    private $aviaDepartureDate;
    /** @var  string Количество пунктов пересадок */
    private $aviaLocationNumber;
    /** @var  string Коды аэропортов */
    private $aviaLocationCodes;
    /** @var  string Имя пассажира */
    private $aviaFirstName;
    /** @var  string Фамилия пассажира */
    private $aviaLastName;
    /** @var  string Код резервирования */
    private $aviaReservationCode;
    /** @var  string Список платежных систем, доступных клиенту при выборе способа оплаты на платежной странице. */
    private $paymentSystems;
    /** @var  string Платежная система, которая первой отобразится плательщику на платежной странице. По умолчанию - card */
    private $defaultPaymentSystem = 'card';
    /** @var  string SecretKey торговца */
    private $merchantSecretKey;

    /**
     * @param string $merchantAccount Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay
     * @param string $merchantSecretKey SecretKey торговца
     */
    public function __construct($merchantAccount, $merchantSecretKey)
    {
        $this->merchantAccount = $merchantAccount;
        $this->merchantSecretKey = $merchantSecretKey;
    }

    /**
     * Устанавливает: Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay
     * @param string $merchantAccount Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay
     * @return $this
     */
    public function setMerchantAccount($merchantAccount)
    {
        $this->merchantAccount = $merchantAccount;
        return $this;
    }

    /**
     * Получить: Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay
     * @return string Идентификатор продавца. Данное значение присваивается Вам со стороны WayForPay
     */
    public function getMerchantAccount()
    {
        return $this->merchantAccount;
    }

    /**
     * Устанавливает: Тип авторизации.
     * @param string $merchantAuthType Тип авторизации.
     * @return $this
     */
    public function setMerchantAuthType($merchantAuthType)
    {
        $this->merchantAuthType = $merchantAuthType;
        return $this;
    }

    /**
     * Получить: Тип авторизации.
     * @return string Тип авторизации.
     */
    public function getMerchantAuthType()
    {
        return $this->merchantAuthType;
    }

    /**
     * Получить: Список вариантов для значения MerchantAuthType
     * @return array Список вариантов для значения MerchantAuthType
     */
    public function getMerchantAuthTypeList()
    {
        return array(
            'SimpleSignature',
            'ticket',
            'password',
        );
    }

    /**
     * Устанавливает: Доменное имя веб-сайта торговца
     * @param string $merchantDomainName Доменное имя веб-сайта торговца
     * @return $this
     */
    public function setMerchantDomainName($merchantDomainName)
    {
        $this->merchantDomainName = $merchantDomainName;
        return $this;
    }

    /**
     * Получить: Доменное имя веб-сайта торговца
     * @return string Доменное имя веб-сайта торговца
     */
    public function getMerchantDomainName()
    {
        return $this->merchantDomainName;
    }

    /**
     * Устанавливает: Тип транзакции.
     * @param string $merchantTransactionType Тип транзакции.
     * @return $this
     */
    public function setMerchantTransactionType($merchantTransactionType)
    {
        $this->merchantTransactionType = $merchantTransactionType;
        return $this;
    }

    /**
     * Получить: Тип транзакции.
     * @return string Тип транзакции.
     */
    public function getMerchantTransactionType()
    {
        return $this->merchantTransactionType;
    }

    /**
     * Получить: Список вариантов для значения MerchantTransactionType
     * @return array Список вариантов для значения MerchantTransactionType
     */
    public function getMerchantTransactionTypeList()
    {
        return array(
            'AUTO',
            'AUTH',
            'SALE',
        );
    }

    /**
     * Устанавливает: Тип безопасности для прохождение транзакции.
     * @param string $merchantTransactionSecureType Тип безопасности для прохождение транзакции.
     * @return $this
     */
    public function setMerchantTransactionSecureType($merchantTransactionSecureType)
    {
        $this->merchantTransactionSecureType = $merchantTransactionSecureType;
        return $this;
    }

    /**
     * Получить: Тип безопасности для прохождение транзакции.
     * @return string Тип безопасности для прохождение транзакции.
     */
    public function getMerchantTransactionSecureType()
    {
        return $this->merchantTransactionSecureType;
    }

    /**
     * Устанавливает: Язык платежной страницы.
     * @param string $language Язык платежной страницы.
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Получить: Язык платежной страницы.
     * @return string Язык платежной страницы.
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Получить: Список вариантов для значения Language
     * @return array Список вариантов для значения Language
     */
    public function getLanguageList()
    {
        return array(
            'AUTO',
            'RU',
            'UA',
            'EN',
        );
    }

    /**
     * Устанавливает: URL, на который система должна перенаправлять клиента с результатом платежа.
     * @param string $returnUrl URL, на который система должна перенаправлять клиента с результатом платежа.
     * @return $this
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    /**
     * Получить: URL, на который система должна перенаправлять клиента с результатом платежа.
     * @return string URL, на который система должна перенаправлять клиента с результатом платежа.
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * Устанавливает: URL, на который система должна отправлять ответ с результатом платежа напрямую мерчанту
     * @param string $serviceUrl URL, на который система должна отправлять ответ с результатом платежа напрямую мерчанту
     * @return $this
     */
    public function setServiceUrl($serviceUrl)
    {
        $this->serviceUrl = $serviceUrl;
        return $this;
    }

    /**
     * Получить: URL, на который система должна отправлять ответ с результатом платежа напрямую мерчанту
     * @return string URL, на который система должна отправлять ответ с результатом платежа напрямую мерчанту
     */
    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    /**
     * Устанавливает: Уникальный номер заказа в системе торговца
     * @param string $orderReference Уникальный номер заказа в системе торговца
     * @return $this
     */
    public function setOrderReference($orderReference)
    {
        $this->orderReference = $orderReference;
        return $this;
    }

    /**
     * Получить: Уникальный номер заказа в системе торговца
     * @return string Уникальный номер заказа в системе торговца
     */
    public function getOrderReference()
    {
        return $this->orderReference;
    }

    /**
     * Устанавливает: Дата размещение заказа
     * @param int $orderDate Дата размещение заказа
     * @return $this
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * Получить: Дата размещение заказа
     * @return int Дата размещение заказа
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Устанавливает: Сумма заказа
     * @param float $amount Сумма заказа
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Получить: Сумма заказа
     * @return float Сумма заказа
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Устанавливает: Валюта заказа UAH
     * @param string $currency Валюта заказа UAH
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Получить: Валюта заказа UAH
     * @return string Валюта заказа UAH
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Устанавливает: Альтернативная сумма заказа
     * @param float $alternativeAmount Альтернативная сумма заказа
     * @return $this
     */
    public function setAlternativeAmount($alternativeAmount)
    {
        $this->alternativeAmount = $alternativeAmount;
        return $this;
    }

    /**
     * Получить: Альтернативная сумма заказа
     * @return float Альтернативная сумма заказа
     */
    public function getAlternativeAmount()
    {
        return $this->alternativeAmount;
    }

    /**
     * Устанавливает: Альтернативная валюта заказа
     * @param string $alternativeCurrency Альтернативная валюта заказа
     * @return $this
     */
    public function setAlternativeCurrency($alternativeCurrency)
    {
        $this->alternativeCurrency = $alternativeCurrency;
        return $this;
    }

    /**
     * Получить: Альтернативная валюта заказа
     * @return string Альтернативная валюта заказа
     */
    public function getAlternativeCurrency()
    {
        return $this->alternativeCurrency;
    }

    /**
     * Устанавливает: Устанавливает интервал, в течении которого заказ может быть оплачен. В секундах
     * @param int $orderTimeout Устанавливает интервал, в течении которого заказ может быть оплачен. В секундах
     * @return $this
     */
    public function setOrderTimeout($orderTimeout)
    {
        $this->orderTimeout = $orderTimeout;
        return $this;
    }

    /**
     * Получить: Устанавливает интервал, в течении которого заказ может быть оплачен. В секундах
     * @return int Устанавливает интервал, в течении которого заказ может быть оплачен. В секундах
     */
    public function getOrderTimeout()
    {
        return $this->orderTimeout;
    }

    /**
     * Устанавливает: Токен карты для рекаренговых списаний
     * @param string $recToken Токен карты для рекаренговых списаний
     * @return $this
     */
    public function setRecToken($recToken)
    {
        $this->recToken = $recToken;
        return $this;
    }

    /**
     * Получить: Токен карты для рекаренговых списаний
     * @return string Токен карты для рекаренговых списаний
     */
    public function getRecToken()
    {
        return $this->recToken;
    }

    /**
     * Добавить довар
     * @param string $productName Наименование заказанного товара
     * @param float $productPrice Цена товара
     * @param int $productCount Количество товара
     * @return $this
     */
    public function addProduct($productName, $productPrice, $productCount)
    {
        $this->productName[] = $productName;
        $this->productPrice[] = $productPrice;
        $this->productCount[] = $productCount;
        return $this;
    }

    public function deleteProducts()
    {
        $this->productName[] = array();
        $this->productPrice[] = array();
        $this->productCount[] = array();
        return $this;
    }

    public function getProducts()
    {
        $products = array();
        foreach ($this->productName as $key => $item) {
            $products[] = array(
                'productName' => $item,
                'productPrice' => $this->productPrice[$key],
                'productCount' => $this->productCount[$key],
            );
        }
        return $products;
    }

    /**
     * Устанавливает: уникальный идентификатор клиента в системе торговца (логин, email и т.д.)
     * @param string $clientAccountId уникальный идентификатор клиента в системе торговца (логин, email и т.д.)
     * @return $this
     */
    public function setClientAccountId($clientAccountId)
    {
        $this->clientAccountId = $clientAccountId;
        return $this;
    }

    /**
     * Получить: уникальный идентификатор клиента в системе торговца (логин, email и т.д.)
     * @return string уникальный идентификатор клиента в системе торговца (логин, email и т.д.)
     */
    public function getClientAccountId()
    {
        return $this->clientAccountId;
    }

    /**
     * Устанавливает: Уникальный идентификатор ресурса.
     * @param string $socialUri Уникальный идентификатор ресурса.
     * @return $this
     */
    public function setSocialUri($socialUri)
    {
        $this->socialUri = $socialUri;
        return $this;
    }

    /**
     * Получить: Уникальный идентификатор ресурса.
     * @return string Уникальный идентификатор ресурса.
     */
    public function getSocialUri()
    {
        return $this->socialUri;
    }

    /**
     * Устанавливает: Имя клиента
     * @param string $clientFirstName Имя клиента
     * @return $this
     */
    public function setClientFirstName($clientFirstName)
    {
        $this->clientFirstName = $clientFirstName;
        return $this;
    }

    /**
     * Получить: Имя клиента
     * @return string Имя клиента
     */
    public function getClientFirstName()
    {
        return $this->clientFirstName;
    }

    /**
     * Устанавливает: Фамилия клиента
     * @param string $clientLastName Фамилия клиента
     * @return $this
     */
    public function setClientLastName($clientLastName)
    {
        $this->clientLastName = $clientLastName;
        return $this;
    }

    /**
     * Получить: Фамилия клиента
     * @return string Фамилия клиента
     */
    public function getClientLastName()
    {
        return $this->clientLastName;
    }

    /**
     * Устанавливает: Адрес клиента
     * @param string $clientAddress Адрес клиента
     * @return $this
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;
        return $this;
    }

    /**
     * Получить: Адрес клиента
     * @return string Адрес клиента
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * Устанавливает: Город клиента
     * @param string $clientCity Город клиента
     * @return $this
     */
    public function setClientCity($clientCity)
    {
        $this->clientCity = $clientCity;
        return $this;
    }

    /**
     * Получить: Город клиента
     * @return string Город клиента
     */
    public function getClientCity()
    {
        return $this->clientCity;
    }

    /**
     * Устанавливает: Штат/Область клиента
     * @param string $clientState Штат/Область клиента
     * @return $this
     */
    public function setClientState($clientState)
    {
        $this->clientState = $clientState;
        return $this;
    }

    /**
     * Получить: Штат/Область клиента
     * @return string Штат/Область клиента
     */
    public function getClientState()
    {
        return $this->clientState;
    }

    /**
     * Устанавливает: Почтовый индекс клиента
     * @param string $clientZipCode Почтовый индекс клиента
     * @return $this
     */
    public function setClientZipCode($clientZipCode)
    {
        $this->clientZipCode = $clientZipCode;
        return $this;
    }

    /**
     * Получить: Почтовый индекс клиента
     * @return string Почтовый индекс клиента
     */
    public function getClientZipCode()
    {
        return $this->clientZipCode;
    }

    /**
     * Устанавливает: Страна клиента в цифровом ISO 3166-1-Alpha 3
     * @param string $clientCountry Страна клиента в цифровом ISO 3166-1-Alpha 3
     * @return $this
     */
    public function setClientCountry($clientCountry)
    {
        $this->clientCountry = $clientCountry;
        return $this;
    }

    /**
     * Получить: Страна клиента в цифровом ISO 3166-1-Alpha 3
     * @return string Страна клиента в цифровом ISO 3166-1-Alpha 3
     */
    public function getClientCountry()
    {
        return $this->clientCountry;
    }

    /**
     * Устанавливает: Email клиента
     * @param string $clientEmail Email клиента
     * @return $this
     */
    public function setClientEmail($clientEmail)
    {
        $this->clientEmail = $clientEmail;
        return $this;
    }

    /**
     * Получить: Email клиента
     * @return string Email клиента
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * Устанавливает: Номер телефона клиента
     * @param string $clientPhone Номер телефона клиента
     * @return $this
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;
        return $this;
    }

    /**
     * Получить: Номер телефона клиента
     * @return string Номер телефона клиента
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Устанавливает: Имя получателя
     * @param string $deliveryFirstName Имя получателя
     * @return $this
     */
    public function setDeliveryFirstName($deliveryFirstName)
    {
        $this->deliveryFirstName = $deliveryFirstName;
        return $this;
    }

    /**
     * Получить: Имя получателя
     * @return string Имя получателя
     */
    public function getDeliveryFirstName()
    {
        return $this->deliveryFirstName;
    }

    /**
     * Устанавливает: Фамилия получателя
     * @param string $deliveryLastName Фамилия получателя
     * @return $this
     */
    public function setDeliveryLastName($deliveryLastName)
    {
        $this->deliveryLastName = $deliveryLastName;
        return $this;
    }

    /**
     * Получить: Фамилия получателя
     * @return string Фамилия получателя
     */
    public function getDeliveryLastName()
    {
        return $this->deliveryLastName;
    }

    /**
     * Устанавливает: Адрес получателя
     * @param string $deliveryAddress Адрес получателя
     * @return $this
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }

    /**
     * Получить: Адрес получателя
     * @return string Адрес получателя
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Устанавливает: Город получателя
     * @param string $deliveryCity Город получателя
     * @return $this
     */
    public function setDeliveryCity($deliveryCity)
    {
        $this->deliveryCity = $deliveryCity;
        return $this;
    }

    /**
     * Получить: Город получателя
     * @return string Город получателя
     */
    public function getDeliveryCity()
    {
        return $this->deliveryCity;
    }

    /**
     * Устанавливает: Штат/Область получателя
     * @param string $deliveryState Штат/Область получателя
     * @return $this
     */
    public function setDeliveryState($deliveryState)
    {
        $this->deliveryState = $deliveryState;
        return $this;
    }

    /**
     * Получить: Штат/Область получателя
     * @return string Штат/Область получателя
     */
    public function getDeliveryState()
    {
        return $this->deliveryState;
    }

    /**
     * Устанавливает: Почтовый индекс получателя
     * @param string $deliveryZipCode Почтовый индекс получателя
     * @return $this
     */
    public function setDeliveryZipCode($deliveryZipCode)
    {
        $this->deliveryZipCode = $deliveryZipCode;
        return $this;
    }

    /**
     * Получить: Почтовый индекс получателя
     * @return string Почтовый индекс получателя
     */
    public function getDeliveryZipCode()
    {
        return $this->deliveryZipCode;
    }

    /**
     * Устанавливает: Страна получателя
     * @param string $deliveryCountry Страна получателя
     * @return $this
     */
    public function setDeliveryCountry($deliveryCountry)
    {
        $this->deliveryCountry = $deliveryCountry;
        return $this;
    }

    /**
     * Получить: Страна получателя
     * @return string Страна получателя
     */
    public function getDeliveryCountry()
    {
        return $this->deliveryCountry;
    }

    /**
     * Устанавливает: Email получателя
     * @param string $deliveryEmail Email получателя
     * @return $this
     */
    public function setDeliveryEmail($deliveryEmail)
    {
        $this->deliveryEmail = $deliveryEmail;
        return $this;
    }

    /**
     * Получить: Email получателя
     * @return string Email получателя
     */
    public function getDeliveryEmail()
    {
        return $this->deliveryEmail;
    }

    /**
     * Устанавливает: Номер телефона получателя
     * @param string $deliveryPhone Номер телефона получателя
     * @return $this
     */
    public function setDeliveryPhone($deliveryPhone)
    {
        $this->deliveryPhone = $deliveryPhone;
        return $this;
    }

    /**
     * Получить: Номер телефона получателя
     * @return string Номер телефона получателя
     */
    public function getDeliveryPhone()
    {
        return $this->deliveryPhone;
    }

    /**
     * Устанавливает: Время отправления рейса
     * @param string $aviaDepartureDate Время отправления рейса
     * @return $this
     */
    public function setAviaDepartureDate($aviaDepartureDate)
    {
        $this->aviaDepartureDate = $aviaDepartureDate;
        return $this;
    }

    /**
     * Получить: Время отправления рейса
     * @return string Время отправления рейса
     */
    public function getAviaDepartureDate()
    {
        return $this->aviaDepartureDate;
    }

    /**
     * Устанавливает: Количество пунктов пересадок
     * @param string $aviaLocationNumber Количество пунктов пересадок
     * @return $this
     */
    public function setAviaLocationNumber($aviaLocationNumber)
    {
        $this->aviaLocationNumber = $aviaLocationNumber;
        return $this;
    }

    /**
     * Получить: Количество пунктов пересадок
     * @return string Количество пунктов пересадок
     */
    public function getAviaLocationNumber()
    {
        return $this->aviaLocationNumber;
    }

    /**
     * Устанавливает: Коды аэропортов
     * @param string $aviaLocationCodes Коды аэропортов
     * @return $this
     */
    public function setAviaLocationCodes($aviaLocationCodes)
    {
        $this->aviaLocationCodes = $aviaLocationCodes;
        return $this;
    }

    /**
     * Получить: Коды аэропортов
     * @return string Коды аэропортов
     */
    public function getAviaLocationCodes()
    {
        return $this->aviaLocationCodes;
    }

    /**
     * Устанавливает: Имя пассажира
     * @param string $aviaFirstName Имя пассажира
     * @return $this
     */
    public function setAviaFirstName($aviaFirstName)
    {
        $this->aviaFirstName = $aviaFirstName;
        return $this;
    }

    /**
     * Получить: Имя пассажира
     * @return string Имя пассажира
     */
    public function getAviaFirstName()
    {
        return $this->aviaFirstName;
    }

    /**
     * Устанавливает: Фамилия пассажира
     * @param string $aviaLastName Фамилия пассажира
     * @return $this
     */
    public function setAviaLastName($aviaLastName)
    {
        $this->aviaLastName = $aviaLastName;
        return $this;
    }

    /**
     * Получить: Фамилия пассажира
     * @return string Фамилия пассажира
     */
    public function getAviaLastName()
    {
        return $this->aviaLastName;
    }

    /**
     * Устанавливает: Код резервирования
     * @param string $aviaReservationCode Код резервирования
     * @return $this
     */
    public function setAviaReservationCode($aviaReservationCode)
    {
        $this->aviaReservationCode = $aviaReservationCode;
        return $this;
    }

    /**
     * Получить: Код резервирования
     * @return string Код резервирования
     */
    public function getAviaReservationCode()
    {
        return $this->aviaReservationCode;
    }

    /**
     * Устанавливает: Список платежных систем, доступных клиенту при выборе способа оплаты на платежной странице.
     * @param string $paymentSystems Список платежных систем, доступных клиенту при выборе способа оплаты на платежной странице.
     * @return $this
     */
    public function setPaymentSystems($paymentSystems)
    {
        $this->paymentSystems = $paymentSystems;
        return $this;
    }

    /**
     * Получить: Список платежных систем, доступных клиенту при выборе способа оплаты на платежной странице.
     * @return string Список платежных систем, доступных клиенту при выборе способа оплаты на платежной странице.
     */
    public function getPaymentSystems()
    {
        return $this->paymentSystems;
    }

    /**
     * Получить: Список вариантов для значения PaymentSystems
     * @return array Список вариантов для значения PaymentSystems
     */
    public function getPaymentSystemsList()
    {
        return array(
            'card',
            'privat24',
            'lpTerminal',
        );
    }

    /**
     * Устанавливает: Платежная система, которая первой отобразится плательщику на платежной странице. По умолчанию - card
     * @param string $defaultPaymentSystem Платежная система, которая первой отобразится плательщику на платежной странице. По умолчанию - card
     * @return $this
     */
    public function setDefaultPaymentSystem($defaultPaymentSystem)
    {
        $this->defaultPaymentSystem = $defaultPaymentSystem;
        return $this;
    }

    /**
     * Получить: Платежная система, которая первой отобразится плательщику на платежной странице. По умолчанию - card
     * @return string Платежная система, которая первой отобразится плательщику на платежной странице. По умолчанию - card
     */
    public function getDefaultPaymentSystem()
    {
        return $this->defaultPaymentSystem;
    }

    protected function getMerchantSecretKey()
    {
        return $this->merchantSecretKey;
    }

    /**
     * Получить: Подпись запроса
     * @return string Подпись запроса
     */
    protected function getMerchantSignature()
    {
        $attrForSignature = array(
            'merchantAccount',
            'merchantDomainName',
            'orderReference',
            'orderDate',
            'amount',
            'currency',
            'productName',
            'productCount',
            'productPrice',
        );

        $values = array();
        foreach ($attrForSignature as $attr) {
            if (empty($this->$attr)) {
                throw new InvalidArgumentException("Argument $attr must be not empty");
            }
            if ($this->{$attr} === null) {
                continue;
            } elseif (is_array($this->{$attr})) {
                foreach ($this->{$attr} as $item) {
                    $values[] = $item;
                }
            } else {
                $values[] = $this->{$attr};
            }
        }

        $string = implode(';', $values);
        $merchantSignature = hash_hmac('md5', $string, $this->merchantSecretKey);
        return $merchantSignature;
    }

    protected function _setDefaultOrderDate()
    {
        $this->setOrderDate(time());
    }

    protected function _setDefaultAmount()
    {
        $amount = 0;
        foreach ($this->productPrice as $price) {
            $amount += $price;
        }
        $this->setOrderDate($amount);
    }

    /**
     * Получить HTML с кнопкой оплаты
     * @param string $text Текст кнопки оплаты
     * @param array $options Атрибуты для кнопки
     * @return string
     */
    public function getButtonPayment($text = 'Send', $options = array())
    {
        if (!$this->getOrderDate()) {
            $this->_setDefaultOrderDate();
        }
        if (!$this->getAmount()) {
            $this->_setDefaultAmount();
        }

        $html = "";
        $html .= "<form method='post' action='" . self::ADDRESS_URL_WAY_FOR_PAY . "' accept-charset='UTF-8>\n";
        foreach ($this as $key => $attr) {
            if ($this->{$key} === null || $key == 'merchantSecretKey') {
                continue;
            } elseif (is_array($this->{$key})) {
                foreach ($this->{$key} as $item) {
                    $html .= "<input type='hidden' name='" . $key . "[]' value='" . $item . "'>\n";
                }
            } else {
                $html .= "<input type='hidden' name='$key' value='" . $this->{$key} . "'>\n";
            }
        }
        $html .= "<input type='hidden' name='merchantSignature' value='" . $this->getMerchantSignature() . "'>\n";

        $attrForButton = '';
        foreach ($options as $key => $value) {
            $attrForButton .= $key . "='$value' ";
        }
        $html .= "<button $attrForButton type='submit'>$text</button>\n";
        $html .= '</form>';

        return $html;
    }
}
