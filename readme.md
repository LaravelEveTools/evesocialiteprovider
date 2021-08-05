
## EVE Online Socialite Provider
Dedicated Socialite provider for laravel based on SeAT's service [eveseat/services/socialite](https://github.com/eveseat/services/tree/master/src/Socialite/EveOnline) <br>
Just without all the other SeAT stuff.

## Installation
Install the composer package. Laravel will automatically call the service provider.
```
composer require laravelevetools/eve-socialite-provider
```

You will need to get application and client key from [Eve's Devloper Portal](https://developers.eveonline.com/) <br>
You can store the values in your .env file.

Make sure you register the client_id, secret and callback url in config/services.php

```php
    'eveonline' => [
        'client_id'    => env('EVE_CLIENT_ID'),
        'client_secret' => env('EVE_CLIENT_SECRET'),
        'redirect'      => env('EVE_CALLBACK_URL')
    ]
```

## Usage

In your controller you will need two functions. A redirect, and a callback function.
make sure in your routes, you define the same callback route as in the developer portal.

```php

use App\Http\Controllers\Controller;
use Laravel\Socialite\Contracts\Factory as Socialite;

class TestLoginController extends Controller
{
    const scopes = []; //define your scopes here
    
    public function redirect(Socialite $social){

        return $social->driver('eveonline')
            ->scopes(self::scopes)
            ->redirect();
    }

    public function callback(Socialite $social){
        
        $eve_data = $social->driver('eveonline')
            ->scopes(self::scopes)
            ->user();

       // Continue with User authentication as you see fit.
    }
}
```