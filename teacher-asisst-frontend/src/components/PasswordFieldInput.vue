<!-- ChildComponent.vue -->
<template>
  <div class="d-flex align-items-center">
    <v-text-field
      :v-model="password"
      :rules="passwordRules"
      :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
      label="Password"
      hint="Minimum 8 characters"
      density="compact"
      counter
      :type="show ? 'text' : 'password'"
      @click:append="show = !show"
    ></v-text-field>
  </div>
</template>

<script>
export default {
  props: {
    password: {
      type: String,
      required: true
    },
    passwordRules: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      show: false,
    };
  },
 
  
};
</script>
