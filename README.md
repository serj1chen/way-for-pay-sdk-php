# way-for-pay-sdk-php
Библиотека для формирования платежа у систему WayForPay


## Пример генерации кнопки оплаты:

    $order = new WayForPay("test_merch_n1", "flk3409refn54t54t*FNJRET");
    $order->addProduct("Процессор Intel Core i5-4670 3.4GHz",1000, 1)
        ->addProduct("Память Kingston DDR3-1600 4096MB PC3-12800", 547.36, 1)
        ->setMerchantDomainName('www.market.ua')
        ->setOrderReference('56')
        ->setOrderDate(1415379863)
        ->setAmount(1547.36)
        ->setCurrency('UAH');

    echo $order->getButtonPayment('Отправить', array('class'=>'paymentOrder', 'id'=>'btnPayment'));
