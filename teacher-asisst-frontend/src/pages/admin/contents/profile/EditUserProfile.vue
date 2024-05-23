<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12">
          <div class="headline">Update Profile Info</div>
        </v-col>
        <v-col cols="12">
          <v-list-item color="#0000" class="profile-text-name ma-4">
            <v-list-item-content
              class="text-center d-flex justify-center flex-column align-center"
            >
              <v-avatar
                class="profile avatar-center-height avatar-shadow"
                color="grey"
                size="78"
              >
                <v-btn @click="onButtonClick" class="upload-btn" icon>
                  <v-icon> mdi-camera </v-icon>
                </v-btn>
                <input
                  ref="uploader"
                  class="d-none"
                  type="file"
                  accept="image/*"
                  :change="onFileChanged"
                />
                <v-img
                  src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                ></v-img>
              </v-avatar>
              <v-list-item-title> Marcus Obrien </v-list-item-title>
              <v-list-item-subtitle>Administrator</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
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
      <v-row>
        <v-col cols="12">
          <v-btn
            rounded
            color="red"
            class="text-none"
            size="small"
            @click="validate"
          >
            Save
          </v-btn>
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
    onButtonClick() {
      this.isSelecting = true;
      window.addEventListener(
        "focus",
        () => {
          this.isSelecting = false;
        },
        { once: true }
      );

      this.$refs.uploader.click();
    },
    onFileChanged(e) {
      this.selectedFile = e.target.files[0];

      // do something
    },
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
<style scoped>
.upload-btn {
  position: absolute !important;
  z-index: 999;
  top: 40px;
  color: cadetblue;
  background: blueviolet;
  background: rgb(125, 198, 163);
  background: linear-gradient(
    50deg,
    rgba(125, 198, 163, 1) 0%,
    rgba(35, 216, 227, 1) 72%
  );
}
</style>
