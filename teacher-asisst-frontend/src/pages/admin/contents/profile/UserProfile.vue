<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" class="d-flex">

          <div class="headline">Profile Info</div>
          <v-spacer></v-spacer>
            <v-btn
              color="red"
              class="text-none"
              size="small"
              to='/admin/profile/edit'
            >
              Edit Profile
            </v-btn>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <v-text-field
            density="compact"
            label="First Name"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            v-model="formData.firstName"
            variant="outlined"
            :rules="requiredRules"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Last Name"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            v-model="formData.lastName"
            variant="outlined"
            :rules="requiredRules"
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="E-Mail"
            v-model="formData.email"
            variant="outlined"
            :rules="emailRules"
            prepend-inner-icon="mdi-email-outline"
          />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Phone Number"
            v-model="formData.phoneNumber"
            variant="outlined"
            counter="10"
            :rules="numberRules"
            prepend-inner-icon="mdi-cellphone"
          />
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <v-text-field
            label="Gender*"
            density="compact"
            v-model="formData.firstName"
            variant="outlined"
            :rules="requiredRules"
          />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Date of birth"
            v-model="formData.lastName"
            variant="outlined"
            :rules="requiredRules"
          />
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <v-text-field
            density="compact"
            label="Role *"
            v-model="formData.firstName"
            variant="outlined"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            :rules="requiredRules"
          />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            ref="address"
            label="Address*"
            v-model="formData.lastName"
            variant="outlined"
            prepend-inner-icon="mdi-account-arrow-left-outline"
            :rules="[
              () => !!address || 'This field is required',
              () =>
                (!!address && address.length <= 25) ||
                'Address must be less than 25 characters',
              addressCheck,
            ]"
            counter="25"
          />
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>
<script>
export default {
  data: () => ({
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
