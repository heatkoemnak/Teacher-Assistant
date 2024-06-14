<template>
  <div class="center-container">
    
    <ta-card class="card">
      <h1 class="title">
        <span class="teacher-title">Teacher</span>
        <span class="assistant-title">Assistant</span>
      </h1>
      <div class="v-form">
        <v-form
          ref="register"
          v-model="valid"
          lazy-validation
          class="login-form"
        >
          <div class="form-group">
            <div class="text-field">
              <v-text-field
                v-model="credentials.first_name"
                label="First Name"
                :rules="[required]"
                variant="outlined"
                counter
                   density="compact"
              ></v-text-field>
            </div>
          </div>
          <div class="form-group">
            <div class="text-field">
              <v-text-field
                v-model="credentials.last_name"
                label="Last Name"
                :rules="[required]"
                variant="outlined"
                counter
                   density="compact"
              ></v-text-field>
            </div>
          </div>
          <div class="form-group">
            <div class="text-field">
              <v-text-field
                v-model="credentials.email"
                label="Email"
                :rules="[required, isEmail]"
                variant="outlined"
                   density="compact"
              ></v-text-field>
            </div>
          </div>
          <div class="form-group">
            <div class="text-field">
              <v-text-field
                  v-model="credentials.password"
                  :rules="passwordRules"
                  :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                  prepend-icon="mdi-lock"
                  label="Password"
                   hint="Minimum 8 characters"
                  density="compact"
                  counter
                  :type="show ? 'text' : 'password'"
                  @click:append="show = !show"
                  variant="outlined"
                ></v-text-field>
            </div>
          </div>
          <div class="form-group">
            <div class="text-field">
              <v-text-field
                v-model="credentials.password_confirmation"
                label="Confirm Password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                :rules="[(v) => !!v || 'Confirm Password is required']"
                variant="outlined"
                 density="compact"
                @click:append="show = !show"
              ></v-text-field>
            </div>
          </div>
          <div class="checkbox d-flex">
            <input type="checkbox" id="terms" class='ma-3' v-model="terms" />
            <label for="terms" 
              >I agree to the terms <a href="#">Teacher Assistant</a></label
            >
          </div>
          <div class="text-center ma-3">
            <span
              >Already have an account?
              <router-link to="/login-user">Log In</router-link>
              <!-- <a href="#" @click="toggleMode">Log In</a></span -->
          </span>
          </div>
          <div class="form-group full-width-button">
            <ta-btn
              type="submit"
              :disabled="!valid"
              @click="handleRegister"
              label="Admin Register"
            />
          </div>
        </v-form>
      </div>
    </ta-card>
  </div>
</template>
<script>
import taBtn from "@/components/taBtn.vue";
import taCard from "@/components/taCard.vue";
import { mapActions } from "vuex";

export default {
  components: {
    taBtn,
    taCard,
  },
  data() {
    return {
      valid: false,
      show: false,
      credentials: {
        first_name:'',
        last_name:'',
        email: "",
        password: "",
        password_confirmation:""
      },

      passwordRules: [
        (v) => !!v || "Password is needed",
        (v) => v.length >= 8 || "Password is to short",
      ],

    };
  },
  methods: {
    ...mapActions(["register"]),
    async handleRegister() {
      if (this.$refs.register.validate()) {
      try {
        await this.register(this.credentials);
        this.$router.push('login')
      } catch (error) {
        console.error("Register failed", error);
      }
      }
    },
    required: (value) => !!value || "Required.",
    counter: (value) => value.length >= 3 || "min 3 characters",
    isEmail: (value) => {
      const pattern =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return pattern.test(value) || "Invalid e-mail.";
    },
  },
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
  gap: 2px;
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
.text-field {
  margin-bottom: 1rem;
  position: relative;
}
</style>
