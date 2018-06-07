# NGstates
A stand-alone database of Nigeria states and local government manager written for php developers.

# Download
`composer require coderatio/ngstates` or clone this repo to use it mannually. _We don't recommend manual download._

## What you can do with it.
1. Add states
1. Add state
1. Add state local governments
1. Add state local government
1. Get all states
1. Get state
1. Get state local governments
1. Get state local government
1. Update state
1. Update state local governments
1. Update state local government
1. Delete state
1. Delete state local government

## Usage
```php
// If you downloaded the library using composer
require 'vendor/autoload.php'; 

// If you didn't download the library via composer.
require 'src/NGStates.php';

$ngStates = new Coderatio\NGStates\NGStates(); 
/* 
* Note: You can use the helper function which is an instance of NGStates::class. 
* e.g ngstates()->getStates();
*/ 

print_r($ngStates->getStates()); // Print all the states
exit;
```

### Fetch records
```php
// Get states
$ngStates->getStates();

// This will return an array of all states.
```

```php
// Get State
$ngStates->getState(string|int $stateNameOrId);

// e.g
$ngStates->getState(26); 

// OR
$ngStates->getState('Nasarawa State');
```

```php
// Get state local governments
$ngStates->getStateLocals(string|int $stateNameOrId);

// e.g
$ngStates->getStateLocals(26);

// OR
$ngStates->getStateLocals('Nasarawa State');
```

```php
// Get state local government
$ngState->getStateLocal(string|int $stateNameOrId, string|int $localNameOrId);

// e.g
$ngStates->getStateLocal(26, 1);

// OR
$ngStates->getStateLocal('Nasarawa State', 'Lafia');
```

### Add new records
```php
// Add multiple states to database
$ngStates->addStates(array $statesData);

// e.g
$ngStates->addStates([
    ['state' => [
        'id' => 38,
        'name' => 'Demo State'
        'locals' => [
            'id' => 1,
            'name' => 'Demo LGA One'
        ]
    ]],
    ['state' => [
        'id' => 39,
        'name' => 'Demo State Two'
        'locals' => [
            'id' => 1,
            'name' => 'Demo LGA One'
        ]
    ]],
]);

```

```php
// Add a state
$ngStates->addState(array $stateData);

// e.g
$ngStates->addState([
  'id' => 38,
  'name' => 'Demo State',
  'locals' => [
    [
      'id' => 1,
      'name' => 'Demo LGA One'
    ],
    [
      'id' => 2,
      'name' => 'Demo LGA Two',
    ]
  ]
]);
```
```php
// Add state local governments
$ngStates->addStateLocals(string|int $stateNameOrId, array $localsData);

// e.g
$ngStates->addStateLocals(38, [
    [
        'id' => 3,
        'name' => 'Demo LGA Three',
    ],
    [
        'id' => 4,
        'name' => 'Demo LGA Four'
   ]
]);
```

```php
// Add single local government
$ngStates->addStateLocal(string|int $stateNameOrId, array $localData);

// e.g
$ngStates->addStateLocal(38, [
    'id' => 5,
    'name' => 'Demo LGA Five'
]);
```

### Update records
```php
// Update state
$ngStates->updateState(string|int $stateNameOrId, array $stateData);

// e.g
$ngStates->updateState(38, [
    'name' => 'Demo State Edited',
    'locals' => [
        [
            'id' => 1,
            'name' => 'Demo LGA One Edited'
        ]
    ]
]);
```

```php
// Update state local governments
$ngStates->updateStateLocals(string|int $stateNameOrId, array $localsData);

// e.g
$ngStates->updateStateLocals(38, [
    [
        'id' => 1,
        'name' => 'Demo LGA One Updated'
    ],
    [
        'id' => 2,
        'name' => 'Demo LGA Two Updated'
    ]
]);

```

```php
// Update state local
$ngStates->updateStateLocal(string|int $stateNameOrId, array $localData);

// e.g
$ngStates->updateStateLocal(38, [
    [
        'id' => 1,
        'name' => 'Demo LGA Changed'
    ]
]);
```

### Delete records
```php
// Delete state
$ngStates->deleteState(int $stateNameOrId);

// e.g
$ngStates->deleteState(38);
```

```php
// Delete state local government
$ngStates->deleteStateLocal(string|int $stateNameOrId, int $stateLocalId);

// e.g
$ngStates->deleteStateLocal(38, 1); // Will delete local government with the ID 1.
```

## Contribution

To contribute, kindly fork the repo and send a pull request or find me on <a href="https://twitter.com/josiahoyahaya">Twitter</a>.

## Licence
This project is licenced under MIT License. Read through the <a href="https://github.com/coderatio/ngstates/blob/master/LICENSE">license here</a>.
