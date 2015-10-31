<?php

include_once "WayForPay.php";


/**
 * Принять платеж (Purchase)
 *
 * Class WayForPay
 * @author Chenakal Serhii
 */
class ResponsePayment extends WayForPay
{
    /** @var  string Уникальный номер заказа в системе торговца */
    private $orderReference;
    /** @var  string hash_hmac */
    private $merchantSignature;
    /** @var  string Сумма заказа */
    private $amount;
    /** @var  string Валюта заказа */
    private $currency;
    /** @var  string Код авторизации - присваивается банком */
    private $authCode;
    /** @var  string email плательщика */
    private $email;
    /** @var  string Номер телефона плательщика */
    private $phone;
    /** @var  string Дата создания запроса в psp (UTC) */
    private $createdDate;
    /** @var  string Дата процессирования транзакции (UTC) */
    private $processingDate;
    /** @var  string Маскированный номер карты (44****4444) */
    private $cardPan;
    /** @var  string Типа карты: Visa/MasterCard */
    private $cardType;
    /** @var  string Страна карты */
    private $issuerBankCountry;
    /** @var  string Имя Банка карты */
    private $issuerBankName;
    /** @var  string Токен карты для рекаренговых списаний */
    private $recToken;
    /** @var  string статус транзакции */
    private $transactionStatus;
    /** @var  string Причина отказа */
    private $reason;
    /** @var  string Код отказа */
    private $reasonCode;
    /** @var  string Комиссия psp */
    private $fee;
    /** @var  string Платежная система, через которую был осуществлен платеж. */
    private $paymentSystem;

    public function __construct($merchantAccount, $merchantSecretKey)
    {
        parent::__construct($merchantAccount, $merchantSecretKey);

        if(!empty($_POST)){
            foreach($this as $key => $value){
                if(!empty($_POST[$key])){
                    $this->{$key} = $_POST[$key];
                }
            }
        }
    }

    /**
     * Получить: Идентификатор продавца
     * @return string Идентификатор продавца
     */
    public function getMerchantAccount()
    {
        return $this->merchantAccount;
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
     * Получить: hash_hmac
     * @return string hash_hmac
     */
    public function getMerchantSignature()
    {
        return $this->merchantSignature;
    }

    /**
     * Получить: Сумма заказа
     * @return string Сумма заказа
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Получить: Валюта заказа
     * @return string Валюта заказа
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Получить: Код авторизации - присваивается банком
     * @return string Код авторизации - присваивается банком
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Получить: email плательщика
     * @return string email плательщика
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Получить: Номер телефона плательщика
     * @return string Номер телефона плательщика
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Получить: Дата создания запроса в psp (UTC)
     * @return string Дата создания запроса в psp (UTC)
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Получить: Дата процессирования транзакции (UTC)
     * @return string Дата процессирования транзакции (UTC)
     */
    public function getProcessingDate()
    {
        return $this->processingDate;
    }

    /**
     * Получить: Маскированный номер карты (44****4444)
     * @return string Маскированный номер карты (44****4444)
     */
    public function getCardPan()
    {
        return $this->cardPan;
    }

    /**
     * Получить: Типа карты: Visa/MasterCard
     * @return string Типа карты: Visa/MasterCard
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Получить: Страна карты
     * @return string Страна карты
     */
    public function getIssuerBankCountry()
    {
        return $this->issuerBankCountry;
    }

    /**
     * Получить: Имя Банка карты
     * @return string Имя Банка карты
     */
    public function getIssuerBankName()
    {
        return $this->issuerBankName;
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
     * Получить: статус транзакции
     * @return string статус транзакции
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Получить: Причина отказа
     * @return string Причина отказа
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Получить: Код отказа
     * @return string Код отказа
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Получить: Комиссия psp
     * @return string Комиссия psp
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Получить: Платежная система, через которую был осуществлен платеж.
     * @return string Платежная система, через которую был осуществлен платеж.
     */
    public function getPaymentSystem()
    {
        return $this->paymentSystem;
    }

    /**
     * Получить: Подпись запроса
     * @return string Подпись запроса
     */
    protected function generateMerchantSignature()
    {
        $attrForSignature = array(
            'merchantAccount',
            'orderReference',
            'amount',
            'currency',
            'authCode',
            'transactionStatus',
            'reasonCode',
        );

        $values = array();
        foreach ($attrForSignature as $attr) {
            if (empty($this->$attr)) {
                continue;
            }
            $values[] = $this->{$attr};
        }

        $string = implode(';', $values);
        $merchantSignature = hash_hmac('md5', $string, $this->merchantSecretKey);
        return $merchantSignature;
    }

    protected function getStatusesOnSuccess()
    {
        return array(
            'InProcessing',
            'Approved',
        );
    }

    /**
     * Валидация ответа. Если true значить оплата пройшла или все хорошо
     * @return bool
     */
    public function validation()
    {
//        if(!$response = $this->generateMerchantSignature()){
//            return false;
//        }
//        return $this->merchantSignature == $response;

        return in_array($this->getTransactionStatus(), $this->getStatusesOnSuccess());
    }
}