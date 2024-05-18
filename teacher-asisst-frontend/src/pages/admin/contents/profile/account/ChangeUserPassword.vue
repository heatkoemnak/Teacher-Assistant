<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12">
          <div class="headline">Change Password</div>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            v-model="password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock-outline"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            hint="At least 8 characters"
            label="Current Password"
            name="input-10-1"
            variant="outlined"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            v-model="password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock-outline"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            hint="At least 8 characters"
            label="New Password"
            name="input-10-1"
            variant="outlined"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            v-model="password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            prepend-inner-icon="mdi-lock-outline"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            hint="At least 8 characters"
            label="Confirm New Password"
            name="input-10-1"
            variant="outlined"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-btn
            rounded
            color="red"
            class="text-none mx-2"
            size="small"
            @click="validate"
            >Save Change
          </v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>
<script>
export default {
  data: () => ({
    show1: false,
    show2: true,
    password: "",
    rules: {
      required: (value) => !!value || "Required.",
      min: (v) => v.length >= 8 || "Min 8 characters",
      emailMatch: () => `The email and password you entered don't match`,
    },
    valid: false,
    calenderModal: false,
    formLoading: false,
    positions: [
      "Trainee",
      "Working Student",
      "Intern",
      "Junior",
      "Senior",
      "V-Level",
      "C-Level",
    ],
    formData: {
      jobs: [{}],
    },
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
  methods: {
    deleteRow(index) {
      this.formData.jobs.splice([index], 1);
    },
    addRow() {
      this.formData.jobs.push({});
    },
    moveUp(index) {
      let temp1 = this.formData.jobs[index];
      let temp2 = this.formData.jobs[index - 1];
      this.$set(this.formData.jobs, index, temp2);
      this.$set(this.formData.jobs, index - 1, temp1);
    },
    moveDown(index) {
      let temp1 = this.formData.jobs[index];
      let temp2 = this.formData.jobs[index + 1];
      this.$set(this.formData.jobs, index, temp2);
      this.$set(this.formData.jobs, index + 1, temp1);
    },
    validate() {
      if (this.$refs.form.validate()) {
        console.log("submit");
        this.formLoading = true;
        // Timeout Function only for showing loading progress
        setTimeout(() => {
          alert(JSON.stringify(this.formData));
          this.formLoading = false;
          this.reset();
        }, 4000);
      }
    },
    validateDate(dateRange) {
      if (dateRange.length === 2) {
        this.calenderModal = false;
      }
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
  },
};
</script>
