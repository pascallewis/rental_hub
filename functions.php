```php
<?php

function clean($data)
{
    return htmlspecialchars(
        trim($data),
        ENT_QUOTES,
        'UTF-8'
    );
}

function generateLandlordCode()
{
    return 'LL' . strtoupper(substr(md5(uniqid()), 0, 8));
}

function generateReceiptNo()
{
    return 'RCP-' . date('YmdHis');
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['landlord_id']);
}

function landlordName()
{
    return $_SESSION['landlord_name'] ?? '';
}
?>
```
