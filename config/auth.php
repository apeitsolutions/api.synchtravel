<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'sanctum',
            'provider' => 'booking_customers',
        ],
         'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'attendance' => [
            'driver' => 'session',
            'provider' => 'attendances',
        ],
        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],
        'customer' => [
            'driver' => 'session',
            'provider' => 'customer_subcriptions',
        ],
        'Role_manager' => [
            'driver' => 'session',
            'provider' => 'role_managers',
        ],
        'companies' => [  // <-- your custom guard
            'driver' => 'session',
            'provider' => 'companies',
        ],
        'booking_customers' => [
            'driver' => 'sanctum',
            'provider' => 'booking_customers',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],
         'customer_subcriptions' => [
            'driver' => 'eloquent',
            'model' => App\Models\CustomerSubcription\CustomerSubcription::class,
        ],
        'attendances' => [
            'driver' => 'eloquent',
            'model' => App\Models\Attendance::class,
        ],
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],
        'role_managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\CustomerSubcription\RoleManager::class,
        ],
        'companies' => [  // <-- your custom provider
            'driver' => 'eloquent',
            'model' => App\Models\companies::class,
        ],
        'booking_customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\booking_customer::class,
        ],


        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 120,
            'throttle' => 120,
        ],
        'customer_subcriptions' => [
            'provider' => 'customer_subcriptions',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'attendances' => [
            'provider' => 'attendances',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'employees' => [
            'provider' => 'employees',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'role_managers' => [
            'provider' => 'role_managers',
            'table' => 'password_resets',
            'expire' => 60,
        ],
        'booking_customers' => [
            'provider' => 'booking_customers',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
