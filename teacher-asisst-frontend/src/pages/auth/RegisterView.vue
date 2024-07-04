<template>
  <div class="center-container">
    <v-progress-linear
              :active="IsLoading"
              :indeterminate="IsLoading"
              color="deep-purple-accent-4"
              absolute
              bottom
            ></v-progress-linear>
    <div class="pa-10">
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
        <v-alert
        v-if="errorMessage"
        type="error"
        dismissible
        @click="closeError"
      >
        {{ errorMessage }}
      </v-alert>
          <div class="form-group">
              <v-text-field
                v-model="credentials.first_name"
                label="First name"
                :rules="[required]"
                 class="custom-focus-ring"
                variant="outlined"
                counter
                   density="compact"
              ></v-text-field>
          </div>
          <div class="form-group">
              <v-text-field
                v-model="credentials.last_name"
                label="Last name"
                :rules="[required]"
                variant="outlined"
                counter
                   density="compact"
              ></v-text-field>
          </div>
          <div class="form-group">
              <v-text-field
                v-model="credentials.email"
                label="Email Address"
                :rules="[required, isEmail]"
                variant="outlined"
                   density="compact"
              ></v-text-field>
          </div>
          <div class="form-group">
              <v-text-field
                  v-model="credentials.password"
                  :rules="passwordRules"
                  :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                  label="Password"
                   hint="Minimum 8 characters"
                  density="compact"
                  counter
                  :type="show ? 'text' : 'password'"
                  @click:append="show = !show"
                  variant="outlined"
                ></v-text-field>
          </div>
          <div class="form-group">
              <v-text-field
              class="rounded-xl"
                v-model="credentials.password_confirmation"
                label="Confirm Password"
                :inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show ? 'text' : 'password'"
                :rules="[(v) => !!v || 'Confirm Password is required']"
                variant="outlined"
                 density="compact"
                @click:append="show = !show"
              ></v-text-field>
          </div>
          <div class="checkbox d-flex justify-center">
            <input type="checkbox" id="terms" class='ma-3' v-model="terms" />
            <label for="terms"
              >I agree to the terms <a href="#">Teacher Assistant</a></label
            >
          </div>
          <div class="text-center ma-3">
            <span
              >Already have an account?
              <router-link :to="{name:'login'}">Log In</router-link>
          </span>
          </div>
          <div class="form-group full-width-button">
            <ta-btn
            class='text-none'
              type="submit"
              :disabled="!valid"
              @click="handleRegister"
              label="Admin Register"
            />
          </div>
        </v-form>
      </div>
    </div>
  </div>
</template>
<script>
import taBtn from "@/components/taBtn.vue";
// import taCard from "@/components/taCard.vue";
import { mapGetters,mapMutations } from "vuex";

export default {
  components: {
    taBtn,
    // taCard,
  },
  data() {
    return {
      IsLoading: false,
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
  computed: {
    ...mapGetters(['errorMessage',]),
  },
  methods: {
    ...mapMutations(['clearErrors']),
    async handleRegister() {
      if (this.$refs.register.validate()) {
        try {
          this.IsLoading = true;
        await this.$store.dispatch('register', this.credentials);
      } catch (error) {
        console.error('Registration error:', error);
      } finally {
          this.IsLoading = false;
        }
      }
    },
    closeError() {
      this.clearErrors();
    },
    watch: {
    errorMessage(newVal) {
      if (newVal) {
        setTimeout(() => {
          this.clearErrors();
        }, 5000); // Auto-close after 5 seconds
      }
    },
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
  width: 400px;
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
  display: flex;
  align-items: center;
  /* position: relative; */
  /* background-color: #e84d72; */
}
</style>
