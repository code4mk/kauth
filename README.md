# `kauth`
kauth is JWT API Authentication ( jwt-auth ) for laravel


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
## attempt special

* username credential will be optional as  (id,email,username) .  (ex: facebook username)
* `usernames` describe which columns you want to match for username

```php
attempt(["usernames"=>["id","email","name"],"username"=>"request query for username","password=>123456"]);
```
## Kauth::check()

```php
Kauth::check();
```
## Kauth::id()

```php
Kauth::id();
```

## Kauth::refreshToken()

```php
Kauth::refreshToken()
```

## Kauth::logout()

```php
Kauth::logout();
```

## Kauth::logoutOtherDevices()

```php
Kauth::logoutOtherDevices();
```

<a href="https://twitter.com/0devco" target="_blank" ><p align="center" ><img src="https://raw.githubusercontent.com/0devco/docs/master/.devco-images/logo-transparent.png"></p></a>
