<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer location="right" permanent>
        <template v-slot:prepend>
          <!-- <v-avatar
            class="profile avatar-center-heigth avatar-shadow"
            color="grey"
            size="78"
          >
            <v-btn @click="onButtonClick" class="upload-btn"  icon>
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
          </v-avatar> -->
          <!-- <v-list-item
            lines="two"
            prepend-avatar="https://randomuser.me/api/portraits/women/81.jpg"
            subtitle="Logged in"
            title="Jane Smith"
          ></v-list-item> -->
        </template>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item
            prepend-icon="mdi-home-city"
            title="Profile Info"
            value="profile"
            to="/admin/profile/baseinfo"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-account"
            title="Account"
            value="account"
            to="/admin/profile/account"
          ></v-list-item>
          <!-- <v-list-item
            prepend-icon="mdi-lock"
            title="Change Password"
            value="change"
            to="/admin/profile/account"
          ></v-list-item> -->
        </v-list>
      </v-navigation-drawer>
      <v-main max-height="550" style="overflow-y: auto"
        ><v-progress-linear
          :active="formLoading"
          indeterminate
          top
          absolute
          color="primary accent-4"
        ></v-progress-linear>
        <router-view></router-view>
      </v-main>
    </v-layout>
  </v-card>
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

<!-- <template lang="">
  <v-app id="app">
    <div class="headline">Profile Info</div>
    <v-card class="d-flex">
      <div class="d-flex flex-column align-center">
        <v-avatar size="78">
          <v-img
            alt="John"
            src="https://cdn.vuetifyjs.com/images/john.jpg"
          ></v-img>
        </v-avatar>
        <v-list-item
          subtitle="Network Engineer"
          title="Marcus Obrien"
        ></v-list-item>
        <v-list class="mx-auto" max-width="256" border>
          <v-list-item prepend-icon="mdi-inbox-arrow-down" title="Inbox" link>
            <template v-slot:append>
              <v-badge color="error" content="6" inline></v-badge>
            </template>
          </v-list-item>

          <v-list-item
            prepend-icon="mdi-send"
            title="Sent Mail"
            link
          ></v-list-item>

          <v-list-item prepend-icon="mdi-delete" title="Trash" link>
            <template v-slot:append>
              <v-badge color="info" content="12" inline></v-badge>
            </template>
          </v-list-item>

          <v-list-item
            prepend-icon="mdi-alert-circle"
            title="Spam"
            link
          ></v-list-item>
        </v-list>
      </div>
    </v-card>
    <v-main>
      <v-progress-linear
        :active="formLoading"
        indeterminate
        top
        absolute
        color="primary accent-4"
      ></v-progress-linear>
      <v-container>
        <v-form
          ref="form"
          v-model="valid"
          lazy-validation
          class="elevation-5 rounded-lg px-5 py-7"
        >
          <v-row>
            <v-col cols="12">
              <div class="headline">Profile Info</div>
            </v-col>
            <v-col cols="12" md="6" class="mb-0">
              <v-text-field
                label="First Name"
                v-model="formData.firstName"
                outlined
                prepend-inner-icon="mdi-account-arrow-right-outline"
                :rules="requiredRules"
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Last Name"
                v-model="formData.lastName"
                outlined
                prepend-inner-icon="mdi-account-arrow-left-outline"
                :rules="requiredRules"
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="E-Mail"
                v-model="formData.email"
                outlined
                :rules="emailRules"
                prepend-inner-icon="mdi-email-outline"
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                label="Phone Number"
                v-model="formData.phoneNumber"
                outlined
                counter="10"
                :rules="numberRules"
                prepend-inner-icon="mdi-cellphone"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-btn color="primary" @click="validate"> Save Change </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-container>
    </v-main>
  </v-app>
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
<style lang=""></style> -->
