<template>
  <v-col cols="12" md="4" sm="6">
    <v-btn rounded="xl" size="x-large" block :color="color">Rounded xl</v-btn>
  </v-col>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  color: {
    type: String,
    default: '#E84D72'
  }
});
</script>

<style scoped>

</style>
