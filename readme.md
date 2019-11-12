# Sunday microservice for generating PDF forms.

## Install

1. Run composer install
2. Follow instructions for Lumen installation: https://lumen.laravel.com/docs/6.x

_Note: this project's public/index.php file had been modified to run better in WHM/cPanel environment. This the the application files go in /lumen (create it) and the public directory contents go in /public_html_ 

## Webservice
**POST** for `/render/zalogkotelezettinyilatkozat` with the following array structure encoded in JSON to receive a filled PDF copy of 'Groupama: NYILATKOZAT BIZTOSÍTÁSI SZERZŐDÉSHEZ' form.
Use the data key to pass the JSON string. 
```php
$data = [
    'ados' => [
        'ingatlan' => [
            'iranyitoszam' => "1111",
            'telepules' => "ŰŐ űő",
            'utcanev' => "Zöldkő utca",
            'utcaszam' => "9",
            'emelet' => "",
            'ajto' => "A",
            'helyrajziszam' => "123/456/789",
            'alapterulet' => "180",
            'jelzalogjogosult' => "Gerő Gábor",
            'kolcsonszerzodesszama' => "85489731963",
            'kolcsonszerzodestartalma' => "4189498465",
            'kolcsonosszege' => "69420420"
        ],
        'szerzodes' => [
            'biztositasneve1' => "826",
            'biztositasneve2' => "Társasház bizt",
            'szerzodesszama' => "BŐŰ-912247",
            'biztositasiosszeg' => "70250000",
        ]
    ],
    'biztositott' => [
        'nev' => "Molnár Bálint",
        'iranyitoszam' => "1144",
        'telepules' => "Budapest",
        'utcanev' => "Ond Vezér Park",
        'utcaszam' => "4",
        'emelet' => "2",
        'ajto' => "65",
        'szuletes' => [
            "hely" => "Budapest",
            "ev" => "1994",
            "ho" => "03",
            "nap" => "03",
        ],
        'anyjaneve' => "Posteiner Nóra",
    ],
    'szerzodo' => [
        'nev' => "Molnár Bálint",
        'iranyitoszam' => "1144",
        'telepules' => "Budapest",
        'utcanev' => "Ond Vezér Park",
        'utcaszam' => "4",
        'emelet' => "2",
        'ajto' => "65",
        'szuletes' => [
            "hely" => "Budapest",
            "ev" => "1994",
            "ho" => "03",
            "nap" => "03",
        ],
        'anyjaneve' => "Posteiner Nóra",
    ],
    'bank' => [
        'folyosito' => "Budapest Bank",
        'meghatalmazott' => "Budapest Bank",
        'adatkezelo' => "Budapest Bank",
        'szerzodesszam' => "15034895"
    ],
    'kelt' => [
        'ev' => "2019",
        'ho' => '11',
        'nap' => '11'
    ]
];
```

## Bug reporting
Please open an issue in Sunday's bug tracker software by sending a mail to helpdesk at sundayit.hu