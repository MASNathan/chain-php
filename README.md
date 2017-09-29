# chain-php
Chainable native functions and more

## Usage

```php
$domain = explode('@', 'andre.r.flip@gmail.com');
$domain = end($domain);
$domain = trim($domain);

$domain = with('andre.r.flip@gmail.com')->explode('@', '$$')->end()->trim()->get();
// 'gmail.com'
```

or

```php
$key = with('some.service.3rdparty.integration')
    ->explode('.', '!!')
    ->array_map(function ($value) {
        return $value == '3rdparty' ? 'local' : $value;
    }, '!!')
    ->implode('.', '!!')
    ->get();
// 'some.service.local.integration'
```

Inspired by [Sebastiaan Luca Pipe item](https://blog.sebastiaanluca.com/enabling-php-method-chaining-with-a-makeshift-pipe-operator)