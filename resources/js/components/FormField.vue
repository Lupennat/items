<template>
    <DefaultField :field="currentField" :full-width-content="currentField.fullWidth" :show-help-text="showHelpText">
        <template #field>
            <draggable
                v-model="sortableItems"
                item-key="index"
                :disabled="!currentField.draggable"
                handle=".sortable-handle"
            >
                <template #item="{ element, index }">
                    <div class="py-1">
                        <div class="flex py-1 gap-2">
                            <button
                                v-if="currentField.draggable"
                                type="button"
                                style="cursor: move"
                                class="sortable-handle px-4"
                            >
                                <Icon type="menu" />
                            </button>
                            <template v-if="isSelect">
                                <SelectField
                                    :disabled="!canEditRow"
                                    :is-searchable="currentField.searchable"
                                    :attribute="currentField.attribute"
                                    :options="currentField.options"
                                    :placeholder="currentField.placeholder"
                                    :error="hasErrors(index)"
                                    :mode="mode"
                                    :original-value="element.value"
                                    @select-changed="updateItemSelect(index, $event)"
                                    :nullable="false"
                                />
                            </template>
                            <input
                                v-else
                                :value="element.value"
                                :type="currentField.inputType"
                                :disabled="!canEditRow"
                                v-on:keyup="updateItem(index, $event)"
                                :name="currentField.name + '[' + index + ']'"
                                autocomplete="new-password"
                                :class="{ 'border-red-500': hasErrors(index) }"
                                class="flex-1 form-control form-input form-input-bordered"
                            />
                            <BasicButton
                                v-if="canDeleteRow"
                                @click="removeItem(index)"
                                type="button"
                                tabindex="0"
                                class="flex items-center appearance-none cursor-pointer text-red-500 hover:text-red-600 active:outline-none active:ring focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600"
                                :title="__('Delete')"
                            >
                                <Icon type="minus-circle" />
                            </BasicButton>
                        </div>
                        <template v-if="hasErrors(index)">
                            <HelpText class="mt-2 help-text-error" v-for="error in getErrors(index)" >
                                {{ error }}
                            </HelpText>
                        </template>
                    </div>
                </template>
            </draggable>
            <div class="flex mt-2 justify-center" v-if="canAddRow">
                <button
                    @click="addItem"
                    type="button"
                    class="cursor-pointer focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 focus:ring-offset-4 dark:focus:ring-offset-gray-800 rounded-lg mx-auto text-primary-500 font-bold link-default mt-3 px-3 rounded-b-lg flex items-center"
                >
                    <Icon type="plus-circle" />
                    <span class="ml-1">{{ currentField.actionText }}</span>
                </button>
            </div>
        </template>
    </DefaultField>
</template>

<script>
    import draggable from 'vuedraggable';
    import SelectField from './SelectField.vue';

    import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';

    export default {
        mixins: [DependentFormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: { draggable, SelectField },

        data() {
            return {
                value: [],
                newItem: '',
                arrayErrors: []
            };
        },

        methods: {
            setInitialValue() {
                this.value = this.currentField.value || [];
            },
            onSyncedField() {
                this.value = this.currentField.value || [];
            },

            fill(formData) {
                formData.append(this.currentField.attribute, JSON.stringify(this.value || []));
            },

            addItem() {
                this.value.push('');
            },

            updateItem(index, event) {
                this.value.splice(index, 1, event.target.value);
            },

            updateItemSelect(index, value) {
                this.value.splice(index, 1, value);
            },

            removeItem(index) {
                this.value.splice(index, 1);
            },

            hasErrors(index) {
                return Object.keys(this.arrayErrors).find(key => key.split('.')[1] == index) !== undefined;
            },

            getErrors(index) {
                const key = Object.keys(this.arrayErrors).find(key => key.split('.')[1] == index);
                return key ? this.arrayErrors[key] : [];
            }
        },
        computed: {
            isSelect() {
                return this.currentField.inputType === 'select';
            },
            canAddRow() {
                return !this.maxReached && !this.currentlyIsReadonly && this.currentField.canAddRow;
            },
            canDeleteRow() {
                return !this.currentlyIsReadonly && this.currentField.canDeleteRow;
            },
            canEditRow() {
                return !this.currentlyIsReadonly && this.currentField.canEditRow;
            },
            maxReached() {
                return this.currentField.max !== null && this.value.length + 1 > this.currentField.max;
            },
            sortableItems: {
                get() {
                    return this.value.map((value, index) => {
                        return {
                            index: index,
                            value: value
                        };
                    });
                },
                set(values) {
                    this.value = values.map(item => {
                        return item.value;
                    });
                }
            }
        },
        watch: {
            errors: {
                handler: function (errors) {
                    if (errors.errors.hasOwnProperty(this.currentField.attribute)) {
                        this.arrayErrors = JSON.parse(errors.errors[this.currentField.attribute][0]);
                    }
                },
                deep: true
            }
        }
    };
</script>
