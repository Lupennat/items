<template>
    <PanelItem :index="index" :field="field">
        <template v-slot:value>
            <template v-if="field.style === 'group'">
                <span v-if="fieldValue.length > 0">{{ fieldValue.join(', ') }}</span>
                <p v-else>&mdash;</p>
            </template>
            <template v-else>
                <template v-if="fieldValue.length > 0">
                    <span v-for="item in fieldValue" v-text="item" class="block" />
                </template>
                <p v-else>&mdash;</p>
            </template>
        </template>
    </PanelItem>
</template>

<script>
    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field', 'index'],
        computed: {
            fieldHasValue() {
                return Array.isArray(this.field.value);
            },
            usesCustomizedDisplay() {
                return this.field.usesCustomizedDisplay && Array.isArray(this.field.displayedAs);
            },
            fieldValue() {
                if (!this.usesCustomizedDisplay && !this.fieldHasValue) {
                    return [];
                }
                return this.field.displayedAs || this.field.value;
            }
        }
    };
</script>
