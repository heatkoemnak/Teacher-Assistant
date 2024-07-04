<template>
  <div>
    <button @click="generatePassword">Generate Random Password</button>
    <p v-if="password">Generated Password: {{ password }}</p>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
