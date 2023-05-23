1. [Requirements](#Requirements)
2. [Installation](#Installation)
3. [Usage](#Usage)
4. [Validation](#validation)
5. [Additional options](#additional-options)
6. [Credits](#credits)

## Requirements

- `php: ^7.4 | ^8`
- `laravel/nova: ^4`



## Installation

```
composer require lupennat/items
```

## Usage

Laravel Nova array items field with sorting, validation & many [display options](#additional-options)

```php
use Lupennat\Items\Items;
```

```php
function fields() {
    return [
        Items::make('Emails'),
    ]
}
```

and be sure to [cast](https://laravel.com/docs/5.7/eloquent-mutators#array-and-json-casting) the property as an array on your eloquent model

```php
public $casts = [
    'emails' => 'array'
];
```

## Validation

Use Laravel's built in [array validation](https://laravel.com/docs/validation#validating-arrays)

```php
Items::make('Emails')->rules([
    'emails.*' => 'email|min:10',
]),
```

Manually setting the attribute may be needed in some cases.

```php
Items::make('Long Text', 'attribute')->rules([
    'attribute.*' => 'email|min:10',
]),
```

## Additional options

| function                  | description                                                     | default          |
| ------------------------- | --------------------------------------------------------------- | ---------------- |
| `->max(number)`           | limit number of items allowed                                   | false            |
| `->draggable()`           | turn on drag/drop sorting                                       | false            |
| `->onlyDraggable()`       | turn on drag/drop sorting and off add,delete,editing            | false            |
| `->inputType(text)`       | text, date, etc                                                 | "text"           |
| `->placeholder($value)`   | the new item input text                                         | "Add a new item" |
| `->actionText($value)`    | value for create button                                         | "Add"            |
| `->disableAddingRows()`   | hide the "add" button                                           | false            |
| `->disableDeletingRows()` | hide the "delete" button                                        | false            |
| `->disableEditingRows()`  | input on readonly mode                                          | false            |
| `->displayAsList()`       | display list on index as list instead of comma separated values | "group"          |

---

# Credits

Items field is based on [Nova Items Field](https://github.com/dillingham/nova-items-field).
