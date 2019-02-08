# kauth
kauth is laravel jwt auth system


# Installation
you can install kauth package by command
```bash
composer require code4mk/kauth
```
# Setup

### 1) Vendor publish.

```bash
php artisan vendor:publish --provider="Kauth\KauthServiceProvider" --tag=config
php artisan vendor:publish --provider="Kauth\KauthServiceProvider" --tag=migations
```
### 2) Config setup

* `config\kauth.php`

# Usage

## `Kauth::attempt()` functions

* guard('name')
```php
// guard name will be user table name
Kauth::guard("users")
```
* socialite()->attempt()
```php
// laravel socialite system
// credential will be only email
Kauth::guard("users")->socialite()
        ->attempt(["email"=>"ex@gmail.com"]);
```
* normal attempt()
```php
// your all desired credentials
// password credentail need
Kauth::guard("users")
  ->attempt(["email"=>"ex@email.com","password"=>1234])
```

~ `attempt() return a jwt token` which you pass with request header (ex:axios header)
