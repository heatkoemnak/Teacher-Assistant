<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" class="d-flex">
          <div class="headline">Account</div>
          <v-spacer></v-spacer>
          <v-btn
            color="red"
            class="text-none"
            size="small"
            to="/admin/profile/edit"
          >
            Edit
          </v-btn>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="username"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            v-model="account.name"
            variant="outlined"
            :rules="requiredRules"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="E-Mail"
            v-model="account.email"
            variant="outlined"
            :rules="emailRules"
            prepend-inner-icon="mdi-email-outline"
          />
        </v-col>
        <!-- <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            v-model="account.password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock-outline"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            hint="At least 8 characters"
            label="Password"
            name="input-10-1"
            variant="outlined"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-col> -->
      </v-row>
    </v-form>
  </v-container>
</template>
<script>
import axios from "@/axios";

export default {
  data: () => ({
    show1: false,
    show2: true,
    account: "",
    password: "Password",
    rules: {
      required: (value) => !!value || "Required.",
      min: (v) => v.length >= 8 || "Min 8 characters",
      emailMatch: () => `The email and password you entered don't match`,
    },
    valid: false,
    requiredRules: [(v) => !!v || "Please fill out this field!"],
    numberRules: [
      (v) => !!v || "Please fill out this field!",
      (v) => Number.isInteger(Number(v)) || "Please enter numbers only!",
    ],
    passwordRules: [(v) => !!v || "Please fill out the field!"],
    emailRules: [
      (v) => !!v || "Please fill out the field!",
      (v) =>
        !v ||
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid",
    ],
  }),

  created() {
    this.getUserAccount();
  },
  methods: {
    async getUserAccount() {
      try {
        const response = await axios.get(`/users/${this.$route.params.id}`);
        this.account = response.data;
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      }
    },
  }

};
</script>
