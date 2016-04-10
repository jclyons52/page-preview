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
$preview = Preview::create();
$preview->fetch('https://somewebsite.com');
echo $preview->render(); // returns bootstrap media link preview
```

for

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
