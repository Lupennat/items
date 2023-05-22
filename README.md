## Nova List Field

Laravel Nova array items field with sorting, validation & many [display options](#additional-options)

### Installation

```
composer require lupennat/list
```

### Usage

```php
use Lupennat\List\List;
```

```php
function fields() {
    return [
        List::make('Emails'),
    ]
}
```

and be sure to [cast](https://laravel.com/docs/5.7/eloquent-mutators#array-and-json-casting) the property as an array on your eloquent model

```php
public $casts = [
    'emails' => 'array'
];
```

### Validation

Use Laravel's built in [array validation](https://laravel.com/docs/validation#validating-arrays)

```php
List::make('Emails')->rules([
    'emails.*' => 'email|min:10',
]),
```

Manually setting the attribute may be needed in some cases.

```php
List::make('Long Text', 'attribute')->rules([
    'attribute.*' => 'email|min:10',
]),
```

### Additional options

| function                  | description                                                     | default          |
| ------------------------- | --------------------------------------------------------------- | ---------------- |
| `->max(number)`           | limit number of items allowed                                   | false            |
| `->draggable()`           | turn on drag/drop sorting                                       | false            |
| `->onlyDraggable()`       | turn on drag/drop sorting and off add,delete,editing            | false            |
| `->fullWidth()`           | increase the width of field area                                | false            |
| `->maxHeight(pixel)`      | limit the height of the list                                    | false            |
| `->inputType(text)`       | text, date, etc                                                 | "text"           |
| `->placeholder($value)`   | the new item input text                                         | "Add a new item" |
| `->actionText($value)`    | value for create button                                         | "Add"            |
| `->disableAddingRows()`   | hide the "add" button                                           | false            |
| `->disableDeletingRows()` | hide the "delete" button                                        | false            |
| `->disableEditingRows()`  | input on readonly mode                                          | false            |
| `->displayAsList()`       | display list on index as list instead of comma separated values | "group"          |

---

# Credits

List field is based on [Nova Items Field](https://novapackages.com/collaborators/dillingham).
