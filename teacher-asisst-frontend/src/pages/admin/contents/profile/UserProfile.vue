<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" class="d-flex">
          <div class="headline">Personal Details</div>
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

        <v-col cols="12" md="6" class="mb-0">
          <v-text-field
            density="compact"
            label="Full Name"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            v-model="data.name"
            variant="outlined"
            readonly
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Code"
            prepend-inner-icon="mdi-account-arrow-right-outline"
            v-model="data.teacher_id"
            variant="outlined"
            readonly
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="E-Mail"
            v-model="data.email"
            variant="outlined"
            :rules="emailRules"
            prepend-inner-icon="mdi-email-outline"
          />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Phone Number"
            v-model="data.phone"
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
            v-model="data.gender"
            variant="outlined"
            :rules="requiredRules"
          />
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field
            density="compact"
            label="Date of birth"
            v-model="data.date_of_birth"
            variant="outlined"
            :rules="requiredRules"
          />
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field
            ref="address"
            label="Address*"
            v-model="data.address"
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
import fakeDataAPI from "../../fakedata";

export default {
  data: () => ({
    valid: false,
    calenderModal: false,
    formLoading: false,
    data: "",
    requiredRules: [(v) => !!v || "Please fill out this field!"],
    // numberRules: [
    //   (v) => !!v || "Please fill out this field!",
    //   (v) => Number.isInteger(Number(v)) || "Please enter numbers only!",
    // ],
    emailRules: [
      (v) => !!v || "Please fill out the field!",
      (v) =>
        !v ||
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid",
    ],
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
      this.loading = true;
      fakeDataAPI.get("/api/teachers", { params: options }).then((response) => {
        if (this.$route.params.id) {
          this.data = response.data.items.find(
            (item) => item.id == this.$route.params.id
          );
          console.log(this.$route.params.id);
          console.log(this.data);
        } else {
          this.data = response.data.items;
        }
      });
    },
  },
};
</script>
