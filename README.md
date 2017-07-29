# Bandrek

[![Build Status](https://travis-ci.org/Nuwira/bandrek.svg?branch=master)](https://travis-ci.org/Nuwira/bandrek)
[![Total Download](https://img.shields.io/packagist/dt/nuwira/bandrek.svg)](https://packagist.org/packages/nuwira/bandrek)
[![Latest Stable Version](https://img.shields.io/packagist/v/nuwira/bandrek.svg)](https://packagist.org/packages/nuwira/bandrek)

> **Bandrek** is local word in Javanese that means a **lock pick**. We use lock pick to open a padlock if the key is lost.

Bandrek replaces Laravel's password recovery manager to provide code and token when requesting password recovery. This code can be used as token replacement if you are using REST-API to reset password without visiting web interface.
 
Bandrek generate 6 random numeric character as code. Bandrek is also creating 64 characters token that can be used in normal web page interface.

The scenario is when user request password recovery via REST-API, the user can send the credentials (e-mail and password) and the easy readable code to reset the password.

## Installation

Bandrek only supports Laravel 5.4. To install using [Composer](https://getcomposer.org/), just run this command below.

```bash
composer require nuwira/bandrek
```

## Configuration

### Config File
After installed, open `config/app.php` and find this line.
```php
Illuminate\Auth\Passwords\PasswordResetServiceProvider::class
``` 
Comment or remove it and add that line to override Laravel's password reset handling.

```php
Nuwira\Bandrek\BandrekServiceProvider::class
```

### Model File

To be able to send reset password instruction e-mail, open `app/User.php` (the user model file) and find this line.

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
```
Replace that line with this.

```php
use Nuwira\Bandrek\Auth\User as Authenticatable;
```

### Using Custom Notification

Bandrek by default is using e-mail for notification. You can add or replace using your preferred method by extending abstract class `Nuwira\Bandrek\Notification\BandrekNotification`. The token and code are available in `$this->code` and `$this->token` in the notification class.

For example, if you want to send the code using [Gammu SMS notification](https://github.com/laravel-notification-channels/gammu), just install and configure it.

To use SMS and e-mail for code sending, add function in your model that extends `Nuwira\Bandrek\Auth\User` and inject the notification.

**Notification File:** `App\Notifications\ResetPassword.php`

```php
namespace App\Notifications;

use Nuwira\Bandrek\Notification\BandrekNotification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Gammu\GammuChannel;
use NotificationChannels\Gammu\GammuMessage;

class ResetPassword extends BandrekNotification
{
    public function via($notifiable)
    {
        return ['mail', GammuChannel::class];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required.');
    }
    
    public function toGammu($notifiable)
    {
        return (new GammuMessage())
            ->to($phoneNumber)
            ->content('To reset password, use this code: '.$this->code);
    }
}
```

**Model File:** `App\User.php`

```php
namespace App;

use Nuwira\Gembok\Auth\User as BaseUser;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends BaseUser
{
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
```
 
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.