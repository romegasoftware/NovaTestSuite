# nova-test-suite

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/romegasoftware/nova-test-suite.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/romegasoftware/nova-test-suite.svg?style=flat-square)](https://packagist.org/packages/romegasoftware/nova-test-suite)

## Install
`composer require romegasoftware/nova-test-suite --dev`


## Usage
### Generate Resource Test Cases
To get you started run `php artisan nova:test resource_name`. This will generate a Resource test and publish the `NovaResourceTestCase` if it was not already published.

First thing you will need to do after creating a resource tests is filling the `remapResource()` method. This method must map the nova fields names to
the resource properties. It's also not needed to map each property of the resource, just the ones that change or are required to be diferent.

```php
protected function remapResource($resource)
{
    return [
        'location' => $resource->location_id,
        'theme' => $resource->theme_id,
    ];
}
```

The `$resource` parameter is a fresh generated model instance via `factory()` and therefore should hold any necessary values you need. The `$data` parameter is only filled if you call a nova request method with any data like `$this->storeResource(['name' => 'test'])`.

### requests
**get resources**
```php
// retrieve all available resources = viewing the index page
$this->getResources();

// retrieve a single resource = viewing a single resource in detail view
$resource = Resource::factory()->create();
$this->getResources($resource);
```

**store resources**
```php
// resource data is generated behind the scenes with factory()->make()
$this->storeResource();

// also accepts model classes or arrays
$resource = Resource::factory()->make();
$this->storeResource($resource);
$this->storeResource(['name' => 'Vader']);
```

If a resource is stored successfully the returned status code of the response is `201`.

**update resources**
```php
// resource data is generated behind the scenes with factory()->create()
$this->updateResource(['name' => 'Vader']);

// accepts model classes
$resource = Resource::factory()->create();
$resource->name = 'Vader';
$this->updateResource($resource);
```

**delete resources**
```php
// resource data is generated behind the scenes with factory()->create()
$this->deleteResource();

// also accepts model classes, arrays or integers (ids)
$resource = Resource::factory()->create();
$this->deleteResource($resource);
$this->deleteResource(['id' => 12]);
$this->deleteResource(12);
```

### actions
Use `assertHasActions()

### lenses

### filters

### Asserting a request has failed
Since failed nova request return a redirect with status code `301` we introduced a new method `assertNovaFailed()` which checks for this without having to think about what status code a failed nova response returns.

```php
$this->storeResource()
  ->assertNovaFailed();
```

### Testing required fields
To test if your Nova resource is setup correctly and check if all required fields are set as expected you can use the `setNullValuesOn([..])` method, which assignes every key you enter a `null` value for the next request.

```php
$this->setNullValuesOn(['customer', 'number_of_participants'])
  ->storeResource()
  ->assertRequiredFields(['customer', 'number_of_participants']);
```

### Using the default user
By default each nova request method checks whether a request was already authenticated through `actingAs($user, 'api')`. If no user was provided to authenticate the request we will use the `getDefaultUser()` method to authenticate your request. If you want to be explicit about using the default user for a request you can use `$this->beDefaultUser()` which will return the current class, therefore it will also work with chaining e.g. `$this->beDefaultUser()->storeResource()`.

If you want to use your own user for every request you can override the `getDefaultUser()` method.

```php
protected function getDefaultUser()
{
    return $this->yourOwnUser;
}
```

### Debugging failing requests
To debug more easily why your nova request is failing you can chain `dumpErrors()` before you make any assertions about the status. This method will dump all session errors and the json response of the request.

```php
$this->storeResource()
  ->dumpErrors();
```

**Note:** `dumpErrors()` is only for debugging purposes. It should always be removed before committing any code.

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

- [Braden Keith](https://github.com/romegasoftware)
- [Krishan Koenig](https://github.com/Naoray)
- [Erik C. Forés](https://github.com/ConsoleTVs)
- [All Contributors](https://github.com/romegasoftware/NovaTestSuite/contributors)

## Security
If you discover any security-related issues, please email bkeith@romegasoftware.com instead of using the issue tracker.

## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.