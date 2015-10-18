# way-for-pay-sdk-php
Библиотека для платежей системи WayForPay


## Пример работы:

  $order = new WayForPay('test_merchant', 'dhkq3vUi94{Z!5frxs(02ML');
  $order->addProduct('Apple iPhone 6 16GB',1,1);

  $order->setMerchantDomainName('https://wayforpay.com');

  echo $order->getButtonPayment();
