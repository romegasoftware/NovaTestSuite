# nova-testing-helper

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/romegadigital/nova-testing-helper.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/romegadigital/nova-testing-helper.svg?style=flat-square)](https://packagist.org/packages/romegadigital/nova-testing-helper)

## Install
Add the following to your `composer.json`  and run `composer require romegadigital/nova-testing-helper --dev`

```json
//...
"repositories": [
  {
    "type": "git",
    "url": "git@gitlab.com:romegadigitaltools/nova-test-suite.git"
  }
]
```

## Usage
### Generate Resource Test Cases
To get you started run `php artisan nova:test resource_name`. This will generate a Resource test and publish the `NovaResourceTestCase` if it was not already published.

### requests

### actions

### lenses

### filters

### Testing required fields
To test if your Nova resource is setup correctly and check if all required fields are set as expected you can use the `setNullValuesOn([..])` method, which assignes every key you enter a `null` value for the next request.

```php
$this->setNullValuesOn(['customer', 'number_of_participants'])
  ->storeResource()
  ->assertRequiredFields(['customer', 'number_of_participants']);
```

### Using the default user
By default each nova request method accepts a `User` model which will be used to authenticate the request. The usage of a `User` model per request is optional. If you don't pass a user to the nova request by default a brand new user is created for each request.

If you want to use your own user for every request you can override the `getDefaultUser()` method

```php
protected function getDefaultUser()
{
    return $this->yourOwnUser;
}
```

## Testing
Run the tests with:

``` bash
vendor/bin/phpunit
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Krishan Koenig](https://github.com/romegadigital)
- [All Contributors](https://github.com/romegadigital/nova-testing-helper/contributors)

## Security
If you discover any security-related issues, please email krishan.koenig@googlemail.com instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.