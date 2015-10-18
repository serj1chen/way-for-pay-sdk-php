# way-for-pay-sdk-php
Библиотека для формирования платежа у систему WayForPay


## Пример генерации кнопки оплаты:

    $order = new WayForPay('test_merchant', 'dhkq3vUi94{Z!5frxs(02ML');
    $order->addProduct('Apple iPhone 6 16GB',1,1);

    $order->setMerchantDomainName('https://wayforpay.com');

    echo $order->getButtonPayment('Отправить', array('class'=>'paymentOrder', 'id'=>'btnPayment'));
