# Gembok

Gembok replaces Laravel's password recovery manager to provide code and token when requesting password recovery. This code can be used as token replacement if you are using REST-API to reset password without visiting web interface.
 
Gembok generate 6 random numeric character as code. Gembok is also creating 40 character token that can be used in normal web page interface.

The scenario is when user request password recovery via REST-API, the user can send the credentials (e-mail and password) and the token to reset the password.

## Installation

Gembok only supports Laravel 5.4. To install using [Composer](https://getcomposer.org/), just run this command below.

```bash
composer require nuwira/gembok
```

## Configuration

### Config File
After installed, open `config/app.php` and find this line.
```php
Illuminate\Auth\Passwords\PasswordResetServiceProvider::class
``` 
Comment or remove it and add that line to override Laravel's password reset handling.

```php
Nuwira\Gembok\GembokServiceProvider::class
```

### Model File

To be able to send reset password instruction e-mail, open `app/User.php` (the user model file) and find this line.

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
```
Replace that line with this.

```php
use Nuwira\Gembok\Auth\User as Authenticatable;
```
 
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.