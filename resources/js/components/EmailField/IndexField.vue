/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */
<template>
    <span>{{ value }}</span>
</template>

<script>
import {FormatEmailList} from '../../functions/FormatEmailList'

export default {
    props: ['resourceName', 'field', 'value'],

    beforeMount() {
        let emails = [];

        if (this.field.value) {
            emails = JSON.parse(this.field.value);
        }

        this.value = FormatEmailList(emails);
    }
}
</script>
