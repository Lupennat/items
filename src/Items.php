<?php

namespace Lupennat\Items;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Searchable;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Util;

class Items extends Field
{
    use SupportsDependentFields, Searchable;

    public const LIST_STYLE = 'list';

    public const GROUP_STYLE = 'group';

    public $component = 'items';

    /**
     * The label that should be used for the "add row" button.
     *
     * @var string|null
     */
    public $actionText;

    /**
     * Determine if new rows are able to be added.
     *
     * @var bool
     */
    public $canAddRow = true;

    /**
     * Determine if rows are able to be deleted.
     *
     * @var bool
     */
    public $canDeleteRow = true;

    /**
     * Determine if rows are able to be edited.
     *
     * @var bool
     */
    public $canEditRow = true;

    /**
     * Input Type For Add.
     *
     * @var string|null
     */
    public $inputType;

    /**
     * Max Rows.
     *
     * @var int|null
     */
    public $max;

    /**
     * Determine if draggable mode is enabled.
     *
     * @var bool
     */
    public $draggable = false;

    /**
     * The visual style to use when display the items.
     *
     * @var string
     */
    public $style = 'group';

    /**
     * The field's options callback.
     *
     * @var array<string|int, array<string, mixed>|string>|\Closure|callable|\Illuminate\Support\Collection|null
     */
    public $optionsCallback;

    /**
     * Set the validation rules for the field.
     *
     * @return $this
     */
    public function rules($rules)
    {
        if (!is_array($rules)) {
            abort(500, 'Items Field requires array of validation rules');
        }

        $this->rules = [new ArrayRules($rules)];

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return mixed
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (!$this->fillCallback) {
            $this->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                if ($request->exists($requestAttribute)) {
                    $model->$attribute = $this->isValidNullValue($request[$requestAttribute]) ? null : json_decode($request[$requestAttribute], true);
                }
            });
        }

        return parent::fillAttribute($request, $requestAttribute, $model, $attribute);
    }

    /**
     * The label that should be used for the add row button.
     *
     * @return $this
     */
    public function actionText(string $label)
    {
        $this->actionText = $label;

        return $this;
    }

    /**
     * Disable adding new rows.
     *
     * @return $this
     */
    public function disableAddingRows()
    {
        $this->canAddRow = false;

        return $this;
    }

    /**
     * Disable deleting rows.
     *
     * @return $this
     */
    public function disableDeletingRows()
    {
        $this->canDeleteRow = false;

        return $this;
    }

    /**
     * Disable editing rows.
     *
     * @return $this
     */
    public function disableEditingRows()
    {
        $this->canEditRow = false;

        return $this;
    }

    /**
     * Set Max Rows.
     *
     * @return $this
     */
    public function max(int $max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set Input Type.
     *
     * @return $this
     */
    public function inputType(string $inputType)
    {
        $this->inputType = $inputType;

        return $this;
    }

    /**
     * Set the options for the select menu.
     *
     * @param array<string|int, array<string, mixed>|string>|\Closure|callable|\Illuminate\Support\Collection $options
     *
     * @return $this
     */
    public function options($options)
    {
        $this->optionsCallback = $options;

        return $this;
    }

    /**
     * Serialize options for the field.
     *
     * @param bool $searchable
     *
     * @return array<int, array<string, mixed>>
     */
    protected function serializeOptions($searchable)
    {
        $options = value($this->optionsCallback);

        if (is_callable($options)) {
            $options = $options();
        }

        return collect($options ?? [])->map(function ($label, $value) use ($searchable) {
            $value = Util::safeInt($value);

            if ($searchable && isset($label['group'])) {
                return [
                    'label' => $label['group'] . ' - ' . $label['label'],
                    'value' => $value,
                ];
            }

            return is_array($label) ? $label + ['value' => $value] : ['label' => $label, 'value' => $value];
        })->values()->all();
    }

    /**
     * Set draggable mode.
     *
     * @return $this
     */
    public function draggable(bool $draggable = true)
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function onlyDraggable()
    {
        return $this->disableAddingRows()
            ->disableDeletingRows()
            ->disableEditingRows()
            ->draggable(true);
    }

    /**
     * Set the field to display as a list of rows.
     *
     * @return $this
     */
    public function displayAsList()
    {
        $this->style = static::LIST_STYLE;

        return $this;
    }

    /**
     * Prepare the field element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $this->withMeta([
            'options' => $this->serializeOptions($searchable = $this->isSearchable(app(NovaRequest::class))),
        ]);

        return array_merge(parent::jsonSerialize(), [
            'actionText' => $this->actionText ?? __('Add'),
            'inputType' => $this->inputType ?? 'text',
            'max' => $this->max,
            'draggable' => $this->draggable,
            'style' => $this->style,
            'canAddRow' => $this->canAddRow,
            'canDeleteRow' => $this->canDeleteRow,
            'canEditRow' => $this->canEditRow,
            'searchable' => $searchable,
        ]);
    }
}
