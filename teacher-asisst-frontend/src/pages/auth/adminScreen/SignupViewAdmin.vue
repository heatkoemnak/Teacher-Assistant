<template>
  <div class="center-container">
    <taCard class="card">
      <h1 class="title">
        <span class="teacher-title">Teacher</span>
        <span class="assistant-title">Assistant</span>
      </h1>
      <v-form ref="formRef" @submit.prevent="submitForm" class="signup-form">
        <div class="form-column">
          <div class="left-column">
            <div class="form-group">
              <taTextField
                v-model="signupData.firstName"
                label="Full Name"
                required
                :rules="fullNameRules"
              ></taTextField>
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.email"
                label="Email"
                type="email"
                :rules="emailRules"
                required
              ></taTextField>
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.phoneNumber"
                label="Phone"
                type="text"
                required
                :rules="phoneNumberRules"
              ></taTextField>
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.password"
                label="Password"
                type="password"
                :rules="passwordRules"
                required
              ></taTextField>
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.confirmPassword"
                type="password"
                label="Confirm Password"
                :rules="confirmPasswordRules"
                required
              ></taTextField>
            </div>
          </div>
          <div class="right-column">
            <div class="form-group">
              <taFileUpload @fileChange="handleFileChange" />
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.universityName"
                type="text"
                label="University Name"
                required
                :rules="universityNameRules"
              ></taTextField>
            </div>
            <div class="form-group">
              <taTextField
                v-model="signupData.faculty"
                type="text"
                label="Faculty"
                required
                :rules="facultyRules"
              ></taTextField>
            </div>
          </div>
        </div>
        <div class="checkbox">
          <input type="checkbox" id="terms" v-model="terms" />
          <label for="terms"
            >I agree to the terms <a href="#">Teacher Assistant</a></label
          >
        </div>
        <div class="center">
          <span>Already have an account? <a href="#" @click="toggleMode">Log In</a></span>
        </div>
        <div class="form-group full-width-button">
          <taBtn type="submit" @click="submitForm" label="Sign up" />
        </div>
      </v-form>
    </taCard>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from 'axios';
import { useRouter } from "vue-router";
import taBtn from "./../../../components/taBtn.vue";
import taCard from "./../../../components/taCard.vue";
import taFileUpload from "./../../../components/taFileUpload.vue";
import taTextField from "./../../../components/taTextField.vue";

const router = useRouter();

const signupData = ref({
  firstName: "",
  email: "",
  password: "",
  confirmPassword: "",
  phoneNumber: "",
  universityName: "",
  faculty: "",
  profilePicture: null,
});

const handleFileChange = (file) => {
  signupData.value.profilePicture = file;
};

const emailRules = [
  (v) => !!v || "Email is required",
  (v) => /.+@.+\..+/.test(v) || "Email must be valid",
];

const passwordRules = [
  (v) => !!v || "Password is required",
  (v) => v.length >= 6 || "Password must be at least 6 characters",
  (v) => /[A-Z]/.test(v) || "Password must contain at least one uppercase letter",
  (v) => /[a-z]/.test(v) || "Password must contain at least one lowercase letter",
  (v) => /\d/.test(v) || "Password must contain at least one number",
];

const confirmPasswordRules = [
  (v) => !!v || "Confirm Password is required",
  (v) => v === signupData.value.password || "Passwords do not match",
];

const fullNameRules = [
  (v) => !!v || "Full Name is required",
  (v) => v.length >= 3 || "Full Name must be at least 3 characters",
  (v) =>
    /^[A-Za-z\s]+$/.test(v) ||
    "Full Name should contain only alphabetic characters and spaces",
];

const phoneNumberRules = [
  (v) => !!v || "Phone number is required",
  (v) => /^\d{10}$/.test(v) || "Phone number must be 10 digits",
];

const universityNameRules = [
  (v) => !!v || "University name is required",
  (v) => v.length >= 3 || "University name must be at least 3 characters",
];

const facultyRules = [
  (v) => !!v || "Faculty is required",
  (v) => v.length >= 2 || "Faculty must be at least 2 characters",
];

const formRef = ref(null);

const submitForm = () => {
  formRef.value.validate().then((success) => {
    if (success) {
      const formData = new FormData();
      formData.append('firstName', signupData.value.firstName);
      formData.append('email', signupData.value.email);
      formData.append('phoneNumber', signupData.value.phoneNumber);
      formData.append('password', signupData.value.password);
      formData.append('password_confirmation', signupData.value.confirmPassword);
      formData.append('universityName', signupData.value.universityName);
      formData.append('faculty', signupData.value.faculty);
      if (signupData.value.profilePicture) {
        formData.append('profilePicture', signupData.value.profilePicture);
      }

      axios.post('http://localhost:8000/api/register', formData)
        .then(response => {
          console.log(response.data);
          router.push('/admin/login');
        })
        .catch(error => {
          console.error(error);
        });
    } else {
      console.log('Form is not valid');
    }
  });
};


const toggleMode = () => {
  router.push("/admin/login");
};
</script>

<style scoped>
.center-container {
  display: flex;
  justify-content: center;
  align-items: center;
  background-image: url(/teacher-asisst-frontend/src/pages/auth/adminScreen/SignupViewAdmin.vue);
  background-size: cover;
  background-position: center;
  padding: 20px;
}

.card {
  width: 1000px;
  max-height: 100vh;
  margin: 10px 10px;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-image: url("@assets/ta-classroom.jpg");
}

.title {
  display: flex;
  justify-content: center;
  gap: 10px;
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

.center {
  text-align: center;
  font-size: 18px;
}

.signup-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-column {
  display: contents;
}

.left-column,
.right-column {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.full-width-button {
  grid-column: span 2;
  display: flex;
  justify-content: center;
}

.full-width-button taBtn {
  width: 100%;
}

.checkbox {
  display: flex;
  justify-content: center;
  align-items: center;
  color: #004b8d;
  font-size: 15px;
  margin-top: 10px;
}
</style>
