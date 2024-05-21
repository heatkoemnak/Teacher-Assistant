<template>
  <div class="text-field">
    <v-text-field v-model="value" :label="label" :type="type" :variant="variant"></v-text-field>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: "text"
  },
  variant: {
    type: String,
    default: "outlined"
  }
})
const value = ref('');
</script>

<style scoped>
.text-field {
  margin-bottom: 1rem;
  position: relative;
}

</style>
