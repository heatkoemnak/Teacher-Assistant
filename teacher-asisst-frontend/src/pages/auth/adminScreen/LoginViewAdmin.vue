<template>
  <div class="center-container">
    <taCard class="card">
      <h1 class="title">
        <span class="teacher-title">Teacher</span>
        <span class="assistant-title">Assistant</span>
      </h1>
      <div class="v-form">
        <v-form ref="formRef" @submit.prevent="submitForm" class="login-form">
          <div class="form-group">
            <taTextField
              v-model="adminData.email"
              label="Email"
              :rules="emailRules"
              required
            ></taTextField>
          </div>
          <div class="form-group">
            <taTextField
              v-model="adminData.password"
              label="Password"
              type="password"
              :rules="passwordRules"
              required
            ></taTextField>
          </div>
          <div class="checkbox">
            <input type="checkbox" id="register" v-model="register" />
            <label for="register">Remember me</label>
          </div>
          <div class="center">
            <span>Sign up new account? <a href="#" @click="toggleMode">Sign up</a></span>
          </div>
          <div class="form-group full-width-button">
            <taBtn type="submit" @click="submitForm" label="Login as an admin" />
          </div>
        </v-form>
      </div>
    </taCard>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import taBtn from "./../../../components/taBtn.vue";
import taCard from "./../../../components/taCard.vue";
import taTextField from "./../../../components/taTextField.vue";

const router = useRouter();

const adminData = ref({
  email: "",
  password: "",
});

const register = ref(false);

const emailRules = [
  v => !!v || 'Email is required',
  v => /.+@.+\..+/.test(v) || 'Email must be valid',
];

const passwordRules = [
  v => !!v || 'Password is required',
  v => v.length >= 6 || 'Password must be at least 6 characters',
];

const formRef = ref(null);

const submitForm = () => {
  formRef.value.validate().then(success => {
    if (success) {
      console.log("Admin login submitted:");
      router.push("/admin/dashboard");
    } else {
      console.log("Form is not valid");
    }
  });
};

const toggleMode = () => {
  router.push("/admin/signup");
};
</script>

<style scoped>
.center-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-image: url(/teacher-asisst-frontend/src/pages/auth/adminScreen/SignupViewAdmin.vue);
  background-size: cover;
  background-position: center;
  padding: 20px;
}

.card {
  width: 500px;
  max-height: 100vh;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title {
  display: flex;
  justify-content: center;
  font-size: 28px;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.teacher-title {
  color: #365bc2;
  font-style: italic;
}

.assistant-title {
  color: #e84d72;
  font-style: italic;
}

.v-form {
  display: flex;
  justify-content: center;
}

.login-form {
  width: 100%;
  max-width: 400px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.full-width-button {
  display: flex;
  justify-content: center;
}

.checkbox {
  display: flex;
  align-items: center;
  color: #004b8d;
  font-size: 15px;
}

.center {
  text-align: center;
  font-size: 18px;
  margin-bottom: 20px;
}
</style>
