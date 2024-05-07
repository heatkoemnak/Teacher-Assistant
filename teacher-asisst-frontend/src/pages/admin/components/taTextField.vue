<template>
  <v-col cols="12" md="4" sm="6">
    <v-text-field v-model="value" label="Text Field"></v-text-field>
  </v-col>
</template>

<script setup>
import { ref } from 'vue';

const value = ref('');
</script>

<style scoped>

</style>
