<template>
  <div>
    <v-btn :color="color" rounded="xl" size="x-large" block> {{ label }}</v-btn>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  color: {
    type: String,
    default: '#E84D72'
  },
  label: {
    type: String,
    required:true
  }
});
</script>

<style scoped></style>
