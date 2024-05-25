<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer location="right" permanent>
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
                <v-img
                  src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                ></v-img>
              </v-avatar>
              <v-list-item-title> {{ account.name }} </v-list-item-title>
              <v-list-item-subtitle>Administrator</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-col>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item
            prepend-icon="mdi-home-city"
            title="Profile Info"
            value="profile"
            :to="`/admin/profile/baseinfo/${this.$route.params.id}`"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-account"
            title="Account"
            value="account"
            :to="`/admin/profile/baseinfo/${this.$route.params.id}/account`"
          ></v-list-item>
          <v-list-item
          prepend-icon="mdi-lock"
          title="Change Password"
          value="password"
          :to="`/admin/profile/baseinfo/${this.$route.params.id}/change-password`"
        ></v-list-item>
         
        </v-list>
      </v-navigation-drawer>
      <v-main  style="overflow-y: auto ; height: 90vh;"
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
import fakeDataAPI from "../fakedata";

export default {
  data: () => ({
    valid: false,
    formLoading: false,
    account: "",
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
    loading: true,
  }),
  created() {
    this.loadItems();
  },
  methods: {
    loadItems(
      options = {
        page: 1,
        itemsPerPage: 10,
        sortBy: [],
        sortDesc: [],
        search: "",
        departmentId: this.$route.params.id,
      }
    ) {
      fakeDataAPI.get("/api/accounts", { params: options }).then((response) => {
        if (this.$route.params.id) {
          console.log(this.$route.params.id);
          this.account = response.data.items.find(
            (item) => item.id == this.$route.params.id
          );
          console.log(this.account);
        }
      });
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
