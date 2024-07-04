<template>
  <div>
    <v-btn color="#E84D72" rounded="xl" size="large" class="text-none" block> {{ label }}</v-btn>
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
