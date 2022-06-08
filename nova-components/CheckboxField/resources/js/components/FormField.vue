<template>
  <DefaultField :field="field" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <ul v-for="item in field.actions" :key="item.id" :value="item.value">
        <li>
          <input :id="`option-${item.id}`" type="checkbox" :value="item.value" v-model="value" />
          <label :for="`option-${item.id}`" class="ml-1">{{ item.name }}</label>
        </li>
      </ul>
    </template>
  </DefaultField>
</template>

<script>
  import { FormField, HandlesValidationErrors } from "laravel-nova";

  const defaultValue = 0;

  export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    methods: {
      /*
       * Set the initial, internal value for the field.
       */
      setInitialValue() {
        this.value = this.computePerms(this.field.value || 0);
      },

      /**
       * Calculate bitwise components of reduced permission bit.
       */
      computePerms(value) {
        return this.field.actions.filter((i) => (value & i.value) != 0).map((i) => i.value);
      },

      /**
       * Calculate bitwise value of provided permission bits.
       */
      computeBit() {
        return this.value.reduce((prev, curr) => prev | curr, defaultValue);
      },

      /**
       * Fill the given FormData object with the field's internal value.
       */
      fill(formData) {
        formData.append(this.field.attribute, this.computeBit() || defaultValue);
      }
    }
  };
</script>
