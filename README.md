# page-preview

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Creates link previews to pages with thumbnail, title and description

## Install

Via Composer

``` bash
$ composer require jclyons52/page-preview
```

## Usage

``` php
$previewBuilder = PreviewBuilder::create();
$preview = $previewBuilder->fetch('https://somewebsite.com');
echo $preview->render(); // returns bootstrap media link preview
echo $preview->toJson(); // returns json string of preview attributes
```

or do it inline:

```php
PreviewBuilder::create()->fetch('https://somewebsite.com')->render();
```

Use another one of the other default templates:

```php
PreviewBuilder::create()->fetch('https://somewebsite.com')->render('thumbnail');
```
define your own templates:

```php
PreviewBuilder::create()->fetch('https://somewebsite.com')->render('myAwesomeTemplate', '/path/to/template/directory');
```


The data available for you templates will be:

- string $title       - meta title or page title if not found in meta
- string $description - meta description
- string $url         - link url
- array  $images      - array of image urls
- array  $meta        - array of meta values with their names as keys

If you're usign information from tags such as the twitter meta tags (or anything seperated with ':') you may want to use the unFlatten function to get a multi level array.

This meta:
```html
<meta name="twitter:card" content="app">
<meta name="twitter:site" content="@TwitterDev">
<meta name="twitter:description" content="Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.">
<meta name="twitter:app:country" content="US">
<meta name="twitter:app:name:iphone" content="Cannonball">
<meta name="twitter:app:id:iphone" content="929750075">
<meta name="twitter:app:url:iphone" content="cannonball://poem/5149e249222f9e600a7540ef">
<meta name="twitter:app:name:ipad" content="Cannonball">
<meta name="twitter:app:id:ipad" content="929750075">
<meta name="twitter:app:url:ipad" content="cannonball://poem/5149e249222f9e600a7540ef">
<meta name="twitter:app:name:googleplay" content="Cannonball">
<meta name="twitter:app:id:googleplay" content="io.fabric.samples.cannonball">
<meta name="twitter:app:url:googleplay" content="http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef">
```

using unFlatten:
```php
$meta = $preview->meta->unFlatten()['twitter'];
```

Would produce the following array:

```php
[
    "card" => "app",
    "site" => "@TwitterDev",
    "description" => "Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.",
    "app" => [
        "country" => "US",
        "name" => [
            "iphone" => "Cannonball",
            "ipad" => "Cannonball",
            "googleplay" => "Cannonball",
        ],
        "id" => [
            "iphone" => "929750075",
            "ipad" => "929750075",
            "googleplay" => "io.fabric.samples.cannonball",
        ],
        "url" => [
            "iphone" => "cannonball://poem/5149e249222f9e600a7540ef",
            "ipad" => "cannonball://poem/5149e249222f9e600a7540ef",
            "googleplay" => "http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef",
        ],
    ]

];
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jclyons52@gmail.com instead of using the issue tracker.

## Credits

- [Joseph Lyons][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jclyons52/page-preview.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jclyons52/page-preview/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jclyons52/page-preview.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jclyons52/page-preview.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jclyons52/page-preview.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jclyons52/page-preview
[link-travis]: https://travis-ci.org/jclyons52/page-preview
[link-scrutinizer]: https://scrutinizer-ci.com/g/jclyons52/page-preview/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jclyons52/page-preview
[link-downloads]: https://packagist.org/packages/jclyons52/page-preview
[link-author]: https://github.com/jclyons52
[link-contributors]: ../../contributors
