<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <ul class="list-reset">
                <li v-for="(email, index) in emails" class="pb-4">
                    <div class="flex">
                        <input
                                :id="field.name"
                                type="text"
                                class="w-full form-control form-input form-input-bordered"
                                :class="errorClasses"
                                :placeholder="field.name"
                                v-model="email.address"
                                @change="updateValue"
                        />
                        <button
                                class="appearance-none cursor-pointer text-70 hover:text-primary ml-3"
                                @click.prevent="deleteRow(index)"
                        >
                            <icon type="delete" with="20" height="21" />
                        </button>
                    </div>
                </li>
            </ul>

            <a v-on:click="addEmail" class="cursor-pointer">Add another email address</a>

        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    data() {
        return {
            emails: [
                {address: ''},
            ],
        }
    },

    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            if (this.field.value) {
                this.emails = JSON.parse(this.field.value).map(email => {
                    return {address: email}
                });
            }

            if (!this.emails.length) {
                this.emails.push({address: ''});
            }

            this.value = this.field.value || JSON.stringify([]);
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        addEmail() {
            this.emails.push({address: ''})
        },

        updateValue() {
            let value = this.emails.map(email => email.address);

            this.value = JSON.stringify(value);
        },

        deleteRow(index) {
            this.emails.splice(index, 1);

            if (!this.emails.length) {
                this.emails.push({address: ''});
            }

            this.updateValue();
        }
    },
}
</script>
