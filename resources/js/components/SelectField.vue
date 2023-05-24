<template>
    <SearchInput
        v-if="!disabled && isSearchable"
        @input="performSearch"
        @clear="clearSelection()"
        @selected="selectOption($event)"
        :error="error"
        :value="selectedOption"
        :data="filteredOptions"
        :clearable="nullable"
        trackBy="value"
        class="w-full"
        :mode="mode"
    >
        <!-- The Selected Option Slot -->
        <div v-if="selectedOption" class="flex items-center">
            {{ selectedOption.label }}
        </div>

        <template #option="{ selected, option }">
            <!-- Options List Slot -->
            <div class="flex items-center text-sm font-semibold leading-5" :class="{ 'text-white': selected }">
                {{ option.label }}
            </div>
        </template>
    </SearchInput>

    <!-- Select Input Field -->
    <SelectControl
        v-else
        :id="attribute"
        v-model:selected="value"
        @change="handleChange"
        class="w-full"
        :select-classes="{ 'form-input-border-error': error }"
        :options="options"
        :disabled="disabled"
    >
        <option value="" selected :disabled="!nullable">
            {{ placeholder }}
        </option>
    </SelectControl>
</template>
<script>
    export default {
        props: ['disabled', 'isSearchable', 'attribute', 'options', 'error', 'mode', 'nullable', 'originalValue'],

        data: () => ({
            search: '',
            selectedOption: null,
            value: null
        }),

        created() {
            if (this.originalValue) {
                let selectedOption = _.find(this.options, v => v.value == this.originalValue);

                this.$nextTick(() => {
                    this.selectOption(selectedOption);
                });
            }
        },

        watch: {
            originalValue(val) {
                if (val !== this.value) {
                    let selectedOption = _.find(this.options, v => v.value == val);

                    this.$nextTick(() => {
                        this.selectOption(selectedOption, true);
                    });
                }
            } 
        },

        methods: {
            /**
             * Set the search string to be used to filter the select field.
             */
            performSearch(event) {
                this.search = event;
            },
            /**
             * Clear the current selection for the field.
             */
            clearSelection(silent = false) {
                this.selectedOption = '';
                this.value = '';

                if (!silent) {
                    this.$emit('select-changed', this.value);
                }

            },
            /**
             * Select the given option.
             */
            selectOption(option, silent = false) {
                if (_.isNil(option)) {
                    this.clearSelection(silent);
                    return;
                }

                this.selectedOption = option;
                this.value = option.value;

                if (!silent) {
                    this.$emit('select-changed', this.value);
                }

            },

            /**
             * Handle the selection change event.
             */
            handleChange(value) {
                let selectedOption = _.find(this.options, v => v.value == value);
                this.selectOption(selectedOption);
            }
        },

        computed: {
            /**
             * Return the placeholder text for the field.
             */
            placeholder() {
                return this.placeholder || this.__('Choose an option')
            },

            /**
             * Return the field options filtered by the search string.
             */
            filteredOptions() {
                return this.options.filter(option => {
                    return option.label.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                });
            },
        }
    };
</script>
