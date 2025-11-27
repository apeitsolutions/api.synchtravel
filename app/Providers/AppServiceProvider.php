<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['authorization_All_Provider'        => 'Zoho-enczapikey wSsVR61w/R6jD6svyT38dLhpmA4GVFinHRl+0Fvzv3OvTfrLp8cznxfIAgGjSKQaEzFvQWMX9egukBpT12Zdht4tzVlUCCiF9mqRe1U4J3x17qnvhDzIXm1fkRKNLIsKxApuk2ZpFMoq+g==']);
        
        config(['token_AlhijazTours'                => 'r9fdvwRyF35JUnD6xXdRiDELANYjtfASzPmyGol4-1PN461EY50LbXcqkfEfISsOJDrnFDJbuMzPuxTz37zFWGWBVemQGhi2SYLrr-MZ75vJSAiV73z94UOVrDz5P6R-0KjFqr9XR6P2857snQbcKTUn9YNqjBOQQIkXENeO7tmjxdTJs2KUVoXqo6fFyT9TTq99eKe288N-wyanZXxOsfABWPjtSom2oKLVz6vJnn1WeQwHSp7VnzPUqq53rn80eFXNBSMIiEXBdDmlsokRYSa0evDrQKluhnIzMYkRiazxtnkb-z5Xj0tQReTTHsLz1sgnit2mRGGzP4EIdBK8TiLuEN7GD1kmOT3CMreL7ELrI4yxmEbnYyflICtG-ySk3aZkk8iM9mRZlA7CS10Zuj-C0HEBOFW8vMzy4Eq2CET5WN62S1xe0HPAOrDVwO6jDvVpKEMwm-NiyyjkU8oTTlgYpN77pXtfFjKPTF0julnAMC6cPzxZOGBIkRv0']);
        config(['website_Url_AlhijazTours'          => 'https://alhijaztours.net/']);
        config(['mail_Template_Key_AlhijazTours'    => '2d6f.41936cba9708a01.k1.970bae50-661b-11ef-aded-525400e18d05.1919ec23db5']);
        config(['mail_From_Address_AlhijazTours'    => 'noreply@system.alhijaztours.net']);
        config(['mail_Title_AlhijazTours'           => 'Alhijaz Tours.']);
        config(['dashboardURL_AlhijazTours'         => 'https://system.alhijaztours.net']);
        
        config(['token_HaramynHotel'                => 'IoOCQ3ObCcqj3BmtYVUBKTaCIWWoFnQcCorlRUsL-peMCb6m7dlEhXnSJhXXEo7Dh8bG7WQq18wbzMIvAKNk2RtIVKSBc3uUgZASa-0DZ0L5oiwJ9rSktbNb1dM3efA-b7BLH97ryRSj8vglisLUecscxtA1OFPF7kYWWaqDSKxovS9yKw4jBhUWwMrYT306oG2UZgmDpxP-zx6hENsrnFrHXtOqO6e5SA6ZdJsbJmOXZxDq5ZOcLdZ6PgzeQVdnivhXQHA8g3gzQoNuhYo4E1UYNOdTYGS16EvMpOUTxfmhmLz1-hw9SPnIiIzOX9K83qEOptngC4ftezuMmw2cFusTrxrKMvbH8SUqKAiywnTuiyV4yunaolsqVwbR-4PyM6FO8usVBMFf49vNBSO0nh-cdb8imZPtqb4xGeGHHIu5mG7uMAKZaJVbXGpC2eZfjab3NGV9Z-fmSmrDdAmO44ew0Xf0ZIXu4UoJx8a7EfGQRwWl51g5ZF93J0HH']);
        config(['website_Url_HaramynHotel'          => 'https://haramaynhotels.com/']);
        config(['mail_Template_Key_HaramynHotel'    => '2d6f.41936cba9708a01.k1.8532aaf0-5ed5-11ef-ab39-525400cbcb5e.1916f16fb1f']);
        config(['mail_From_Address_HaramynHotel'    => 'noreply@haramaynhotels.com']);
        config(['mail_Title_HaramynHotel'           => 'Haramayn Hotels.']);
        config(['dashboardURL_HaramynHotel'         => 'https://admin.haramaynhotels.com']);
        
        config(['token_AlhijazRooms'                => 'rz6tgS0tlmw7aSb4hquHHn7NGQ44y8vA88pJaeEH-MRtaeH0lvcyDnhWkFAbhq9NwXRuW5CXp9aPjel92eOZumKV8Tf8N6AyUMdiu-I1jWxYx8Q9nypyYsJXkyPZ1iE-ClroFTec5rzV5Yiw0WZUKCKsgDjnvGVtwKV4s5vzdLgev7W8wjGMqIGk9YL8JCwiSlQmSSAqGLR-gfVR1uWHS9CFpDexv3T1Jfj2PNHzR6IGtJgbZIO5pJ3si03xR7K5CjKDndEEMgKoenRdWUl5exJyllX6lroeDGz66e2HEgqAJuYH-mkYez2oJkQuBPbsELcdi9SfCOv0UJLYbKGjfHwZVk7ySmrOVDEnBoNW1TRR02wKoBG5J5gUKh3n-iJ8O3BWvJD6uHIImlQAFvIKiv-1Y5uld6N0dLF0GS5NHt4nXZsDPxk92tBSaAQTY5d2ffDeNbROQ-TFiAA0WJQiIux0DHu3CZBAMw8fQvmwjaoMV5E6FebFE1CKvfmp']);
        config(['website_Url_AlhijazRooms'          => 'https://alhijazrooms.com/']);
        config(['mail_Template_Key_AlhijazRooms'    => '2d6f.41936cba9708a01.k1.e4a42040-6164-11ef-89c4-525400cbcb5e.1917fde0844']);
        config(['mail_From_Address_AlhijazRooms'    => 'noreply@alhijazrooms.com']);
        config(['mail_Title_AlhijazRooms'           => 'Alhijaz Rooms.']);
        config(['dashboardURL_AlhijazRooms'         => 'https://admin.alhijazrooms.com']);
        
        config(['token_SidraTours'                  => 'jdpCfdBIo5sek9BHzOlKIxKVDKyIVwy2WwzIuTlx-W1KTKA0GQhO9OpcV6Lw3DOUKWesNOP2uo0hjwkQ1yUdRps54jZHma7Ru0rti-h255ezpacThjEqQZ2I2dKJXnL-meNI1NqcUoUlX2ODlOtlVF5MxKQ5KEFvNlyoQfBRDxzWb7k5YLNmIML32JmldZ48QOMy6tVmA2Q-MwhB5dSYu6OYpppqIMniOFI42w6UgW6n8LyupWW5QYrILECN65E3uWCLCuFT6ZmficWQy6lqCWN6LGW9utXbwB2LdezbrrMv9TgB-58STN11HZKAvhAFSExauRs4McxihUJ9Xaa9uGivElL1Mw3bZvr2Gy0o6GK84uz57ozLuaw8nI7L-IXMY7NKeQfcqYjggDhrO3YzVa-PDD84dnFkpzRcdQwyn5zm19FubWZ60iyNCBmI8mAzRslpsgEeX-aAQfbjfzBkqDY2MXaQp8JZxb9YYb5N2xvaa92vklAWnYdmSBb2']);
        config(['website_Url_SidraTours'            => 'https://sidratours.com/']);
        config(['mail_Template_Key_SidraTours'      => '2d6f.41936cba9708a01.k1.b6a19770-6c33-11ef-99aa-52540064429e.191c6b2aa67']);
        config(['mail_From_Address_SidraTours'      => 'noreply@sidratours.com']);
        config(['mail_Title_SidraTours'             => 'Sidra Tours.']);
        config(['dashboardURL_SidraTours'           => 'https://admin.sidratours.com']);
        
        config(['token_Alsubaee'                    => 'RsBl0HcA9IsWWAVagvwbb61qo7sj2kVZCgytDt4z-mUTBaJ0x8Ngsm4yj826O48W6HLc3Sv3Wl78S7C53i1JuTPnZXOxNcMBRBPff-V92s9ByaJowvJ6cINFAjbBwT5-eTTNjDNjqamT41wCPfG1GjpnpIZVE7y5PNngJvgZfCjwptsYrAzwPILViyhpprje3Fn2ztwc2DC-z8eSNhhlcGBMRinLIRaUclHtOdOuiPhIfK1DUkB3HFKXNH5X725xYgL631hkwB8OgnPxglxIQRLZR6BP9zVM3KfZQBnUJHZoVw36-m8NzAVeXrFhriRDUtvgVG2YPLSZiKkCDijgxpaNCnGIfX7Wa004xUltHS1gO9ITeEqhqsadfC9U-kzokxdfFi94I1oF3QQhWYU8G5-pb7vGrlknlKuRGfbxM24dPr1Cer0AhFn1CMuzoznBh2M7PeoSi-a0segLaiLThydY3svhUvRWTQgwsebO8aLNmCypLLbPTjXr68Db']);
        config(['website_Url_Alsubaee'              => 'https://alsubaee.com/']);
        config(['mail_Template_Key_Alsubaee'        => '2d6f.2528563ecd42fd9e.k1.bab98200-8650-11ef-9fda-525400ae9113.19271d59620']);
        config(['mail_From_Address_Alsubaee'        => 'noreply@alsubaee.com']);
        config(['mail_Title_Alsubaee'               => 'Alsubaee']);
        config(['mail_Address_Register_Alsubaee'    => 'info@alsubaeeholidays.com']);
        config(['mail_Address_Register_BPC'         => 'reservation@alsubaeeholidays.com']);
        config(['mail_Address_Register_Payment'     => 'accounts1@alsubaeeholidays.com']);
        config(['authorization_Alsubaee'            => 'Zoho-enczapikey wSsVR61y+hDxXfgrnTSlILg9zF8BUwzxQU0o3VGh7iL9Gf2T9Mc+wk3HUFegHKcaQzNsHDETp+gqyhlUgGBcjdgtzlwCDSiF9mqRe1U4J3x17qnvhDzMWGhflRGLLYsJwQpskmhjG88h+g==']);
        
        // config(['token_Alif'                        => 'rz6tgS0tlmw7aSb4hquHHn7NGQ44y8vA88pJaeEH-MRtaeH0lvcyDnhWkFAbhq9NwXRuW5CXp9aPjel92eOZumKV8Tf8N6AyUMdiu-I1jWxYx8Q9nypyYsJXkyPZ1iE-ClroFTec5rzV5Yiw0WZUKCKsgDjnvGVtwKV4s5vzdLgev7W8wjGMqIGk9YL8JCwiSlQmSSAqGLR-gfVR1uWHS9CFpDexv3T1Jfj2PNHzR6IGtJgbZIO5pJ3si03xR7K5CjKDndEEMgKoenRdWUl5exJyllX6lroeDGz66e2HEgqAJuYH-mkYez2oJkQuBPbsELcdi9SfCOv0UJLYbKGjfHwZVk7ySmrOVDEnBoNW1TRR02wKoBG5J5gUKh3n-iJ8O3BWvJD6uHIImlQAFvIKiv-1Y5uld6N0dLF0GS5NHt4nXZsDPxk92tBSaAQTY5d2ffDeNbROQ-TFiAA0WJQiIux0DHu3CZBAMw8fQvmwjaoMV5E6FebFE1CKvfmp']);
        config(['token_Alif'                        => 'gWFfhzUvLGpXyxnW09ewKbhFCGWehNfAr6KU7MSw-pqdggiOvuenpQokc4n74N4DKXiZ8NwzkCPODlvAKVGkwAaJhVzdMKjNvHbot-UK7IIL5gkFeBtcDjfEqsblonc-CKU5Pa0bRKMhIQUxwUB7GCKk0HhPbEvYNzgueiEwKPJs1EbmvxDGBj0SIPKJasHhtYwKrUkl06Z-QyvXAs1JbUxzVaHraViMvytVGEtsRLM8l2mNkz4rGL8mZZ1DKlytzl8CSNL7BB9inBEnPVfJUrgQpBAnHxsV76wTfcIhv0Bs9i3C-LLG7Xxxfhx4TOP9Q1QDK8AYJEZU9lei6JwsSqv2J55H7aPyIcPTvRKQOLKpSYRUnHqF8ag74nHB-y8LhPygSi0N55IPkISbA5Mskw-Gsu79KhwocF7Ewv9J5HjZA7IfIWkfdaepukazrKyb0TVp9NxB0-NTSB8vxIbQThnrfhlRSoY9emZyhRKMHRh3RZQuhBlXsbWuN8eB']);
        config(['website_Url_Alif'                  => 'https://travel.alifvoyage.com/']);
        config(['mail_Template_Key_Alif'            => '2d6f.41936cba9708a01.k1.bd16b210-bbe9-11ef-92a1-52540064429e.193d11797b1']);
        config(['mail_Template_Key_Alif_Login'      => '2d6f.41936cba9708a01.k1.6aa33010-cf69-11ef-b13a-52540064429e.19450e10d91']);
        config(['mail_Template_Key_Alif_Package'    => '2d6f.41936cba9708a01.k1.384b9d50-cf69-11ef-b13a-52540064429e.19450dfc3a5']);
        config(['mail_From_Address_Alif'            => 'noreply@travel.alifvoyage.com']);
        config(['mail_Address_Admin'                => 'info@alifvoyage.com']);
        config(['mail_Title_Alif'                   => 'Alif Voyage.']);
        config(['dashboardURL_Alif'                 => 'https://admin.alifvoyage.com']);
        
        config(['token_UmrahShop'                   => '23GzN0ZUp3XsHEiHYxXmVZX7WOGfNRb4VD424dl7-ukiMFmzmzQ8zgQUdNPzuN9xEw5h1MSQsql1BzQnsnetNaHStNyHiDyzJsU8T-HVNlcGTriQxt1jGxUKatkc33L-pbO6KtR4txHKjcqJS03jJhM2b5PhAvABnHfzlzvzNtk17TmiiYTkbTLNLEE4setJeBk9ziKIl4I-HQuCQF1mIo6MxAvcjfG18UwUbI12amcSdhBeJc9NJ1uYDrwN1pxXF9TN6sxaKsdV7BPjzeko19XLLNRrHCWLA7O955MbOV5xFepf-TzqUaZxBJJNWGIEMzwJnBYIQdShOMcwaEqiYJTXSPLuWZTfpbbySgHYSm3ytl7bMd4jpF8iy4XN-rtUXxXQeQHR5pQNT0tklOiH4r-G2og2cT7Mr07pVniRUYcAKg6TsL1BRT2wDFbxVrafl8UzYovel-IGV64fOFB9985fEPhxkuFBpz47crLzHh4FhR38cZrTJXBuslpN']);
        config(['website_Url_UmrahShop'             => 'https://umrahshop.com/']);
        config(['mail_Template_Key_UmrahShop'       => '2d6f.41936cba9708a01.k1.0d63f700-bd16-11ef-92a1-52540064429e.193d8c7ba70']);
        config(['mail_From_Address_UmrahShop'       => 'noreply@umrahshop.com']);
        config(['mail_From_Dashboard_UmrahShop'     => 'https://admin.umrahshop.com']);
        config(['mail_Title_UmrahShop'              => 'Umrah Shop.']);
        config(['dashboardURL_UmrahShop'            => 'https://admin.umrahshop.com']);
        
        config(['token_HashimTravel'                => 'dMHmbbV614wwXAptkKaAYXZU7VNi4iH9gbOWJvWF-x3rTIsaFM7syf50B7HOrozkGkGXSmMjNaBn8qz1U2oGzqtWqNgn6jBIncZzX-M2uKQ6bBkcHeD4T1dtVfHeH74-qdHpj27fSm2lMy0PfIgGiWjGdNtQb0LaQ4ICnlxfc73jban5uXB9mNdBoHsmaJcFtpq6B6trJeu-EI29jFgUNMiCzmbDwlRHEHPiXyC1LFVuHH6zf4wu7owKUsho6DGq2xFFk6QUniJOkzWtK0w4wyC3oXlKQUqPRBQnx0Swf2fBSmOL-y7hQluZchgiQhV40EkU9yrGaDRJKyzh1fYAfTDaAtCsvX7vFl42YQPbOxokFcSI2vRySMzVviyH-1Fc3263a4ddrmdJSdnJzaSoue-Ztl1gQiwZfY5sEYqTlzyGojfnOUgFHoeUAEOE8IAoDTEIXjrjU-Qmw8e2mvOhYw4XqFYCpQp7G8ufh5Qb3y3QgAwKknE3h7LuwkIr']);
        config(['website_Url_HashimTravel'          => 'https://hashimtravel.com/']);
        config(['mail_Template_Key_HashimTravel'    => '2d6f.41936cba9708a01.k1.e277b1c1-bd16-11ef-92a1-52540064429e.193d8cd2edc']);
        config(['mail_From_Address_HashimTravel'    => 'noreply@hashimtravel.com']);
        config(['mail_From_Dashboard_HashimTravel'  => 'https://admin.hashimtravel.com']);
        config(['mail_Title_HashimTravel'           => 'Hashim Travel.']);
        config(['dashboardURL_HashimTravel'         => 'https://admin.hashimtravel.com']);
        
        config(['token_HaramaynRooms'               => 'gWFfhzUvLGpXyxnW09ewKbhFCGWehNfAr6KU7MSw-pqdggiOvuenpQokc4n74N4DKXiZ8NwzkCPODlvAKVGkwAaJhVzdMKjNvHbot-UK7IIL5gkFeBtcDjfEqsblonc-CKU5Pa0bRKMhIQUxwUB7GCKk0HhPbEvYNzgueiEwKPJs1EbmvxDGBj0SIPKJasHhtYwKrUkl06Z-QyvXAs1JbUxzVaHraViMvytVGEtsRLM8l2mNkz4rGL8mZZ1DKlytzl8CSNL7BB9inBEnPVfJUrgQpBAnHxsV76wTfcIhv0Bs9i3C-LLG7Xxxfhx4TOP9Q1QDK8AYJEZU9lei6JwsSqv2J55H7aPyIcPTvRKQOLKpSYRUnHqF8ag74nHB-y8LhPygSi0N55IPkISbA5Mskw-Gsu79KhwocF7Ewv9J5HjZA7IfIWkfdaepukazrKyb0TVp9NxB0-NTSB8vxIbQThnrfhlRSoY9emZyhRKMHRh3RZQuhBlXsbWuN8eB_HR']);
        config(['website_Url_HaramaynRooms'         => 'https://haramaynrooms.com/']);
        config(['mail_Template_Key_HaramaynRooms'   => '2d6f.41936cba9708a01.k1.b54b8ef0-57f4-11f0-b7ba-fae9afc80e45.197cfbc0d5f']);
        config(['authorization_HaramaynRooms'       => 'Zoho-enczapikey wSsVR60l+xXxBq0un2b4Ie9rm1sGVFmjHU110QGi43f1F/HLpsc9w0LPAlSlSvIXFTU7HGdDp7sqnBdVhDpfit8pnlBSXCiF9mqRe1U4J3x17qnvhDzDXWxUkRGNLo0IwQptm2JgEcwj+g==']);
        config(['mail_From_Address_HaramaynRooms'   => 'noreply@haramaynrooms.com']);
        // config(['mail_From_Address_HaramaynRooms'   => 'info@haramaynrooms.com']);
        config(['mail_From_Dashboard_HaramaynRooms' => 'https://admin.haramaynrooms.com']);
        config(['mail_Title_HaramaynRooms'          => 'Haramayn Rooms.']);
        config(['dashboardURL_HaramaynRooms'        => 'https://admin.haramaynrooms.com']);
        
        config(['token_AlmnhajHotel'                => 'gWFfhzUvLGpXyxnW09ewKbhFCGWehNfAr6KU7MSw-pqdggiOvuenpQokc4n74N4DKXiZ8NwzkCPODlvAKVGkwAaJhVzdMKjNvHbot-UK7IIL5gkFeBtcDjfEqsblonc-CKU5Pa0bRKMhIQUxwUB7GCKk0HhPbEvYNzgueiEwKPJs1EbmvxDGBj0SIPKJasHhtYwKrUkl06Z-QyvXAs1JbUxzVaHraViMvytVGEtsRLM8l2mNkz4rGL8mZZ1DKlytzl8CSNL7BB9inBEnPVfJUrgQpBAnHxsV76wTfcIhv0Bs9i3C-LLG7Xxxfhx4TOP9Q1QDK8AYJEZU9lei6JwsSqv2J55H7aPyIcPTvRKQOLKpSYRUnHqF8ag74nHB-y8LhPygSi0N55IPkISbA5Mskw-Gsu79KhwocF7Ewv9J5HjZA7IfIWkfdaepukazrKyb0TVp9NxB0-NTSB8vxIbQThnrfhlRSoY9emZyhRKMHRh3RZQuhBlXsbWuN8eB_ALMH']);
        config(['website_Url_AlmnhajHotel'          => 'https://almnhajhotels.com/']);
        config(['mail_Template_Key_AlmnhajHotel'    => '2d6f.41936cba9708a01.k1.7aeace40-62fa-11f0-846b-ce0d466a2eb2.19817f8d124']);
        config(['authorization_AlmnhajHotel'        => 'Zoho-enczapikey wSsVR60l+xXxBq0un2b4Ie9rm1sGVFmjHU110QGi43f1F/HLpsc9w0LPAlSlSvIXFTU7HGdDp7sqnBdVhDpfit8pnlBSXCiF9mqRe1U4J3x17qnvhDzDXWxUkRGNLo0IwQptm2JgEcwj+g==']);
        config(['mail_From_Address_AlmnhajHotel'    => 'noreply@almnhajhotels.com']);
        config(['mail_Title_AlmnhajHotel'           => 'Almnhaj Hotels.']);
        config(['dashboardURL_AlmnhajHotel'         => 'https://admin.almnhajhotels.com']);
        
        config(['token_SynchTravel'                 => 'r9fdvwRyF35JUnD6xXdRiDELANYjtfASzPmyGol4-1PN461EY50LbXcqkfEfISsOJDrnFDJbuMzPuxTz37zFWGWBVemQGhi2SYLrr-MZ75vJSAiV73z94UOVrDz5P6R-0KjFqr9XR6P2857snQbcKTUn9YNqjBOQQIkXENeO7tmjxdTJs2KUVoXqo6fFyT9TTq99eKe288N-wyanZXxOsfABWPjtSom2oKLVz6vJnn1WeQwHSp7VnzPUqq53rn80eFXNBSMIiEXBdDmlsokRYSa0evDrQKluhnIzMYkRiazxtnkb-z5Xj0tQReTTHsLz1sgnit2mRGGzP4EIdBK8TiLuEN7GD1kmOT3CMreL7ELrI4yxmEbnYyflICtG-ySk3aZkk8iM9mRZlA7CS10Zuj-C0HEBOFW8vMzy4Eq2CET5WN62S1xe0HPAOrDVwO6jDvVpKEMwm-NiyyjkU8oTTlgYpN77pXtfFjKPTF0julnAMC6cPzxZOGBIAGed']);
        config(['dashboardURL_SynchTravel'          => 'https://system.synchtravel.com']);
        
        config(['token_HaramaynHotelsOld'           => 'IoOCQ3ObCcqj3BmtYVUBKTaCIWWoFnQcCorlRUsL-peMCb6m7dlEhXnSJhXXEo7Dh8bG7WQq18wbzMIvAKNk2RtIVKSBc3uUgZASa-0DZ0L5oiwJ9rSktbNb1dM3efA-b7BLH97ryRSj8vglisLUecscxtA1OFPF7kYWWaqDSKxovS9yKw4jBhUWwMrYT306oG2UZgmDpxP-zx6hENsrnFrHXtOqO6e5SA6ZdJsbJmOXZxDq5ZOcLdZ6PgzeQVdnivhXQHA8g3gzQoNuhYo4E1UYNOdTYGS16EvMpOUTxfmhmLz1-hw9SPnIiIzOX9K83qEOptngC4ftezuMmw2cFusTrxrKMvbH8SUqKAiywnTuiyV4yunaolsqVwbR-4PyM6FO8usVBMFf49vNBSO0nh-cdb8imZPtqb4xGeGHHIu5mG7uMAKZaJVbXGpC2eZfjab3NGV9Z-fmSmrDdAmO44ew0Xf0ZIXu4UoJx8a7EfGQRwWl51g5ZF93J0Vm']);
        config(['dashboardURL_HaramaynHotelsOld'    => 'https://admin.haramaynhotels.com']);
        
        // config(['img_url' => 'https://client1.synchronousdigital.com']);
        // config(['endpoint_project' => 'https://admin.synchronousdigital.com']);
        // config(['token' => 'tXvl2Fdyhoi2LtTO9CdjkZZqqeFdhW2k8emVjh1z-HHFtkuV58UonLoNvFjT22Ib1gPlckhNrfnqbMlHCFrNUa8sgNa9jqeruCST7-Nt4MJqGDgLMW0ET0vo1ckk8Vx-xM391TcC6vW9NldFCkwX54DIropj3olep9LRNUwxqz8kMQWi4LqrE5JEKqoMFICwQMU6Jnq6xzO-lud2CoszZ6W7bjtl8mvOsFfWiRipk72Pu35193Zid09cmjXgaAn8SLXCOF8IxvImTdfcdc4DypJm2eF3nKyCyyl8FsiCzVPymah0-uDSgh2ErLwsu7c1O2nHbpQMlurSQnJ0iPUo0bLYKCOt07o3pMuYax4sPULNMojJ3IH3rxWONIxi-1h5YXyvJb4uzz5wflBwyylp1P-TolaFXpNDTvFtzKgcPlx2hBUiq7kPyzL8nnaEnmfwrn6zy28cB-vaJ59Cl0FZOIfbZnmfwhUHOvpJkje9wLyq95nTnFZpxMuNeT1i']);
        
        $data = DB::table('admin_markups')->get();
        // print_r($data);die();
    
if(isset($data))
{
 foreach($data as $data_markup)
 {
     if($data_markup->added_markup == 'synchtravel')
     {
       
     
       if($data_markup->provider == 'CustomHotel')
  {
       
        config(['CustomHotel' => $data_markup->markup_value]);
  }
  if($data_markup->provider == 'Ratehawk')
  {
      config(['Ratehawk' => $data_markup->markup_value]);  
  }
  if($data_markup->provider == 'TBO')
  {
      config(['TBO' => $data_markup->markup_value]);   
  }
  if($data_markup->provider == 'Travellanda')
  {
      config(['Travellanda' => $data_markup->markup_value]);  
  }
  if($data_markup->provider == 'Hotelbeds')
  {
      //print_r($data_markup->markup_value);die();
  config(['synchtravel_markup_hotelbeds' => $data_markup->markup_value]);
 
  }
   if($data_markup->provider == 'All')
   {
    config(['synchtravel_markup_all' => $data_markup->markup_value]);   
   }
       
       
       
       
       
       
         
     }
     
   ///ended  
     
     if($data_markup->added_markup == 'alhijaz_tours')
     {
       
       
  if($data_markup->provider == 'CustomHotel')
  {
       
        config(['CustomHotel' => $data_markup->markup_value]);
  }
  if($data_markup->provider == 'Ratehawk')
  {
      config(['Ratehawk' => $data_markup->markup_value]);  
  }
  if($data_markup->provider == 'TBO')
  {
      config(['TBO' => $data_markup->markup_value]);   
  }
  if($data_markup->provider == 'Travellanda')
  {
      config(['Travellanda' => $data_markup->markup_value]);  
  }
  if($data_markup->provider == 'Hotelbeds')
  {
  config(['alhijaz_markup_hotelbeds' => $data_markup->markup_value]);
  }
  if($data_markup->provider == 'All')
  {
    config(['alhijaz_markup_all' => $data_markup->markup_value]);   
  } 
       
       
         
     }

 }
}
    }
}
