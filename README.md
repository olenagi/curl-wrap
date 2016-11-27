# CurlWrap

CurlWrap is wrapper of the PHP cURL extension;

### Instalation
To install CurlWrap, simply:

    $ composer require olenagi/curl-wrap
### Requirements

PHP Curl Class works with PHP 5.3, 5.4, 5.5, 5.6, 7.0, 7.1, and HHVM.

### Examples

```php
require __DIR__ . '/vendor/autoload.php';

use olenagi\CurlWrap\Curl;

$curl = new Curl("http://www.example.com");
$response = $curl->get();


if ($response->isOk()) {
    echo "Request was successful!";
} 
```

```php
require __DIR__ . '/vendor/autoload.php';

use olenagi\CurlWrap\Curl;

$curl = new Curl("http://www.example.com");
$curl->setFile($filePath);
$response = $curl->post();


if ($response->isOk()) {
    echo "Request was successful!";
} 
```