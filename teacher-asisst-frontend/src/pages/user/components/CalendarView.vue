<template>
  <div>
    <h3>This is Calendar</h3>
    <VueDatePicker v-model="date" :flow="flow" />
  </div>
</template>

<script setup>
import { ref } from "vue";

const date = ref();
const flow = ref(["month", "year", "calendar"]);
</script>

<style></style>
