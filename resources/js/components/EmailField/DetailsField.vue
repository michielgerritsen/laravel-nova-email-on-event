<template>
    <panel-item :field="field" />
</template>
<script>
import {FormatEmailList} from '../../functions/FormatEmailList'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    beforeMount() {
        let emails = [];

        if (this.field.value) {
            emails = JSON.parse(this.field.value);
        }

        this.field.value = FormatEmailList(emails);
    }
}
</script>
