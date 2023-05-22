<template>
    <div>
        <template v-if="field.style === 'group'">
            <span>{{ fieldValue.length }}</span>
        </template>
        <template v-else>
            <template v-if="fieldValue.length > 0">
                <span v-for="item in fieldValue" v-text="item" class="block" />
            </template>
            <p v-else>&mdash;</p>
        </template>
    </div>
</template>
<script>
    export default {
        props: ['resourceName', 'field'],
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
