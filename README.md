# MJML PHP

A simple php library to consume use MJML rest API

## Installation
```
composer require juanmiguelbesada/mjml-php
```

## How to use

```php
<?php

use Mjml\Client;

$applicationId = '';
$secretKey = '';
$client = new Client($applicationId, $secretKey);

$mjml = '<mjml><mj-body><mj-container><mj-section><mj-column><mj-text>Hello World</mj-text></mj-column></mj-section></mj-container></mj-body></mjml>';
$message = $client->render($mjml);

$to = 'text@example.com';
$subject = 'My awesome email created with mjml';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
mail($to, $subject, $message, $headers);
```