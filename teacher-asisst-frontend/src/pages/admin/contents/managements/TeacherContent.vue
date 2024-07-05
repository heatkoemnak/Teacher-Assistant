<template>
  <v-data-table
    :headers="headers"
    :items="filteredTeachers"
    class="elevation-1 cursor-pointer"
    item-value="name"
    show-select
    :loading="loading"
    height="450"
  >
    <template v-slot:top>
      <v-card-title>Teachers</v-card-title>

      <v-toolbar color="light-blue-darken-4">
        <v-text-field
          v-model="search"
          density="compact"
          label="Search by Name or ID"
          prepend-inner-icon="mdi-magnify"
          variant="outlined"
          hide-details
          class="ml-4"
        ></v-text-field>
        <v-spacer></v-spacer>

        <v-select
          v-model="selectedGender"
          :items="genderOptions"
          label="Filter by Gender"
          class="md-6 mr-4 w-15 grey-lighten-2"
          density="compact"
          hide-details="auto"
          variant="solo"
        ></v-select>
        <v-btn
          class="text-none text-subtitle-1"
          color="#5865f2"
          size="small"
          variant="flat"
          @click="openCreateDialog"
        >
          New Teacher
        </v-btn>
      </v-toolbar>

      <v-dialog v-model="dialogDelete" max-width="500px">
        <v-card>
          <v-toolbar
            dense
            flat
            class="body-2 font-weight-bold px-5"
            color="grey lighten-2"
          >
            Confirm Delete
          </v-toolbar>
          <v-card-text>
            Are you sure you want to delete this teacher?
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="blue-darken-1"
              variant="text"
              @click="closeDeleteDialog"
              >Cancel</v-btn
            >
            <v-spacer></v-spacer>
            <v-btn color="red" @click="deleteItemConfirm">Delete</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialogEdit" max-width="700px">
        <v-card>
          <v-toolbar
            dense
            flat
            class="body-2 font-weight-bold px-5"
            color="grey lighten-2"
          >
            Confirm Edit
          </v-toolbar>
          <v-card-text>
            <v-progress-linear
              :active="IsLoading"
              :indeterminate="IsLoading"
              color="deep-purple-accent-4"
              absolute
              bottom
            ></v-progress-linear>
            <v-form ref="editForm" v-model="valid" lazy-validation>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="teacherToEdit.first_name"
                    :rules="[required, counter]"
                    label="First Name"
                    density="compact"
                  ></v-text-field>
                  <v-text-field
                    v-model="teacherToEdit.last_name"
                    :rules="[required, counter]"
                    label="Last Name"
                    density="compact"
                  ></v-text-field>
                </v-col>

                <v-col cols="6">
                  <v-menu
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="teacherToEdit.dob"
                        label="Date of Birth"
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="teacherToEdit.dob"
                      @input="menu = false"
                      locale="en-us"
                    ></v-date-picker>
                  </v-menu>
                  <v-select
                    v-model="teacherToEdit.gender"
                    :items="genderOptions"
                    label="Gender"
                    density="compact"
                  ></v-select>

                </v-col>
              </v-row>
              <v-text-field
                v-model="teacherToEdit.phone"
                label="Phone"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue-darken-1" variant="text" @click="closeEditDialog"
              >Cancel</v-btn
            >
            <v-spacer></v-spacer>
            <v-btn color="green" @click="editItemConfirm">Edit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialogCreate" max-width="700px">
        <v-card>
          <v-card-text>
            <v-progress-linear
              :active="IsLoading"
              :indeterminate="IsLoading"
              color="deep-purple-accent-4"
              absolute
              bottom
            ></v-progress-linear>
            <v-form ref="createForm" v-model="valid" lazy-validation>
              <v-container>
                <v-toolbar-title class="mb-10"
                  >Create New Teacher</v-toolbar-title
                >
                <v-row>
                  <v-col cols="6">
                    <v-text-field
                      v-model="newTeacher.first_name"
                      :rules="[required, counter]"
                      label="First name"
                      density="compact"
                      variant="outlined"
                    ></v-text-field>
                    <v-text-field
                      v-model="newTeacher.last_name"
                      :rules="[required, counter]"
                      label="Last name"
                      density="compact"
                      variant="outlined"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="6">
                    <v-menu
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="newTeacher.dob"
                        label="Date of Birth"
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                          variant="outlined"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="newTeacher.dob"
                      @input="menu = false"
                      locale="en-us"
                    ></v-date-picker>
                  </v-menu>
                    <v-select
                      v-model="newTeacher.gender"
                      :items="genderOptions"
                      label="Gender"
                      density="compact"
                      variant="outlined"
                    ></v-select>

                  </v-col>
                </v-row>

                <v-text-field
                  v-model="newTeacher.email"
                  prepend-inner-icon="mdi-email"
                  :rules="[required, isEmail]"
                  label="Email Address"
                  density="compact"
                  variant="outlined"
                ></v-text-field>

                <v-text-field
                  v-model="newTeacher.phone"
                  label="Phone"
                  density="compact"
                  variant="outlined"
                ></v-text-field>
                <div class="d-flex align-items-center">
                  <v-text-field
                    v-model="newTeacher.password"
                    :rules="passwordRules"
                    :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    label="Password"
                    hint="Minimum 8 characters"
                    density="compact"
                    variant="outlined"
                    counter
                    :type="show ? 'text' : 'password'"
                    @click:append-inner="show = !show"
                  ></v-text-field>
                  <v-btn @click="generatePassword" class="text-none mx-2" color="primary">
                    Generate
                    <v-icon class="ml-2" style="cursor: pointer;">mdi-refresh</v-icon>
                  </v-btn>
                </div>
                <v-card-actions>
                  <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="closeCreateDialog"
                    >Cancel</v-btn
                  >
                  <v-spacer></v-spacer>
                  <v-btn
                    color="green"
                    :disabled="!valid"
                    class="mr-4"
                    @click="createItemConfirm"
                    >Create</v-btn
                  >
                </v-card-actions>
              </v-container>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-snackbar
        v-model="showSuccessSnackbar"
        :timeout="timeout"
        color="green"
        absolute
        top
      >
        {{ successMessage }}
        <v-btn
          class="text-none"
          color="#5865f2"
          size="small"
          variant="flat"
          @click="showSuccessSnackbar = false"
        >
          Close
        </v-btn>
      </v-snackbar>
      <v-snackbar v-model="showErrorSnackbar" :timeout="timeout" color="red">
        {{ errorMessage }}
        <v-btn text @click="showErrorSnackbar = false">Close</v-btn>
      </v-snackbar>
    </template>

    <template v-slot:progress>
      <v-overlay :value="loading">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </template>

    <template v-slot:item.actions="{ item }" >
      {{console.log(item)}}
      <div class="d-flex align-center justify-end">
          <v-chip color="blue" class="ma-2" :to="`/admin/profile/basic-info/${item.user_id}/personal-details`">views</v-chip>
        <v-icon
          color="blue"
          class="me-2"
          size="small"
          @click="openEditDialog(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon color="red" size="small" @click="openDeleteDialog(item)">
          mdi-delete
        </v-icon>
      </div>
    </template>
  </v-data-table>
</template>
<script>
import axios from "@/axios";
export default {
  data: () => ({
    headers: [
  { title: "Name", value: "fullname" },
  { title: "ID", value: "id" },
  { title: "Email", value: "email" },
  { title: "DOB", value: "dob" },
  { title: "Phone", value: "phone" },
  { title: "Gender", value: "gender" },
  { title: "Action", value: "actions", filterable: false, sortable: false, align: "end" },
],

    dialogEdit: false,
    dialogDelete: false,
    dialogCreate: false,
    loading: true,
    teachers: [],
    search: "",
    deps: [],
    selectedGender: "",
    selectedDep: "",
    flow : ["month", "year", "calendar"],
    genderOptions: [
      { title: "All", value: "" },
      { title: "Male", value: "male" },
      { title: "Female", value: "female" },
      { title: "Other", value: "other" },
    ],
    DepOptions: [],
    teacherToDelete: null,
    teacherToEdit: null,
    newTeacher: {
      first_name: "",
      last_name: "",
      email: "",
      dob:new Date().toISOString().substr(0, 10),
      phone: "",
      gender: "",
      password: "",
      password_confirmation: "",
    },
    menu: false,
    modal: false,
    menuEdit: false,
    valid: false,
    isValidated: true,
    show: false,
    IsLoading: false,
    showSuccessSnackbar: false,
    showErrorSnackbar: false,
    successMessage: "",
    errorMessage: "",
    timeout: 3000,

    passwordRules: [
      (v) => !!v || "Password ist needed",
      (v) => v.length >= 8 || "Password is to short",
    ],
  }),

  created() {
    this.fetchData();
  },
  computed: {

    filteredTeachers() {
      return this.teachers.filter((teacher) => {
        const matchesGender = this.selectedGender
          ? teacher.user.profile.gender === this.selectedGender
          : true;

        const matchesSearch =
          teacher.fullname.toLowerCase().includes(this.search.toLowerCase()) ||
          teacher.id.toString().includes(this.search);
        return matchesGender && matchesSearch
      });
    },

  },
  watch: {
    dialogDelete(val) {
      val || this.closeDeleteDialog();
    },
    dialogEdit(val) {
      val || this.closeEditDialog();
    },
    dialogCreate(val) {
      val || this.closeCreateDialog();
    },
  },
  methods: {
    allowedDates(val) {
      // Example: Disable weekends
      const date = new Date(val);
      return date.getDay() !== 0 && date.getDay() !== 6;
    },
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/teachers");
        this.teachers = response.data
        .map((teacher) => ({
          ...teacher,
          fullname: teacher.user.name,
          email: teacher.user.email,
          dob: teacher.user.profile.dob || "N/A", // Handle null value for DOB
          phone: teacher.user.profile.phone || "N/A", // Handle null value for phone
          gender: teacher.user.profile.gender || "N/A",
        }));
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      } finally {
        this.loading = false;
      }
    },


    openDeleteDialog(item) {
      this.teacherToDelete = item;
      this.dialogDelete = true;
    },

    closeDeleteDialog() {
      this.dialogDelete = false;
      this.teacherToDelete = null;
    },

    async deleteItemConfirm() {
      if (this.teacherToDelete) {
        try {
          await axios.delete(`/teachers/${this.teacherToDelete.id}/delete`);
          this.closeDeleteDialog();
          this.fetchData(); // Refresh the data after deletion
          this.showSuccessSnackbar = true;
          this.successMessage = "Teacher deleted successfully.";
        } catch (error) {
          console.error("Error deleting data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error deleting teacher.";
        }
      }
    },

    openEditDialog(item) {
      this.teacherToEdit = { ...item };
      this.dialogEdit = true;
    },

    closeEditDialog() {
      this.dialogEdit = false;
    },
    async editItemConfirm() {
      if (this.$refs.editForm.validate()) {
        try {
          this.IsLoading = true;
          await axios.put(
            `/teachers/${this.teacherToEdit.id}/edit`,
            this.teacherToEdit
          );
          this.closeEditDialog();
          this.fetchData(); // Refresh the data after edit
          this.showSuccessSnackbar = true;
          this.successMessage = "Teacher edited successfully.";
        } catch (error) {
          console.error("Error updating data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error editing teacher.";
        } finally {
          this.IsLoading = false;
        }
      }
    },

    openCreateDialog() {
      this.dialogCreate = true;
    },

    closeCreateDialog() {
      this.dialogCreate = false;
    },
    async createItemConfirm() {
      if (this.$refs.createForm.validate()) {
        try {
          await axios.post(
            "/teachers/create",
            this.newTeacher
          );
          this.closeCreateDialog();
          this.fetchData(); // Refresh the data after creation
          this.showSuccessSnackbar = true;
          this.successMessage = "Teacher created successfully.";
        } catch (error) {
          console.error("Error creating data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error creating teacher.";
        }
      }
    },
    generatePassword() {
      const length = 12;
      const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=';
      let generatedPassword = '';
      for (let i = 0, n = charset.length; i < length; ++i) {
        generatedPassword += charset.charAt(Math.floor(Math.random() * n));
      }
      this.newTeacher.password = generatedPassword;
      this.newTeacher.password_confirmation = generatedPassword;

    },

    //validation rules

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
