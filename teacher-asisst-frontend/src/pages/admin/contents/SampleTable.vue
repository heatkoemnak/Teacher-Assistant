<template>
  <v-data-table
    :headers="headers"
    :items="filteredTeachers"
    class="elevation-1 cursor-pointer"
    item-value="name"
    show-select
    :loading="loading"
  >
    <template v-slot:top>
      <v-toolbar-title class="ma-6">Teachers</v-toolbar-title>
      <v-toolbar color="light-blue-darken-4">
        <v-text-field
          v-model="search"
          density="compact"
          label="Search by Name or ID"
          prepend-inner-icon="mdi-magnify"
          variant="solo"
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
          Create Teacher
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
      <v-dialog v-model="dialogEdit" max-width="500px">
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
        :active="loading"
        :indeterminate="loading"
        color="deep-purple-accent-4"
        absolute
        bottom
      ></v-progress-linear>
            <v-form ref="editForm" v-model="valid" lazy-validation>
              <v-text-field
                v-model="teacherToEdit.first_name"
                :rules="[(v) => !!v || 'First Name is required']"
                label="First Name"
              ></v-text-field>
              <v-text-field
                v-model="teacherToEdit.last_name"
                :rules="[(v) => !!v || 'Last Name is required']"
                label="Last Name"
              ></v-text-field>
              <v-text-field
                v-model="teacherToEdit.email"
                :rules="[(v) => /.+@.+\..+/.test(v) || 'E-mail must be valid']"
                label="Email"
              ></v-text-field>
              <v-text-field
                v-model="teacherToEdit.dob"
                label="DOB"
              ></v-text-field>
              <v-text-field
                v-model="teacherToEdit.address"
                label="Address"
              ></v-text-field>
              <v-text-field
                v-model="teacherToEdit.phone"
                label="Phone"
              ></v-text-field>
              <v-select
                v-model="teacherToEdit.gender"
                :items="
                  genderOptions
                    .map((option) => option.value)
                    .filter((gender) => gender !== '')
                "
                label="Gender"
              ></v-select>
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
      <v-dialog v-model="dialogCreate" max-width="500px">
        <v-card>
          <v-toolbar
            dense
            flat
            class="body-2 font-weight-bold px-5"
            color="grey lighten-2"
          >
            Create Teacher
          </v-toolbar>
          <v-card-text>
            <v-form ref="createForm" v-model="valid" lazy-validation>
              <v-text-field
                v-model="newTeacher.first_name"
                :rules="[(v) => !!v || 'First Name is required']"
                label="First Name"
              ></v-text-field>
              <v-text-field
                v-model="newTeacher.last_name"
                :rules="[(v) => !!v || 'Last Name is required']"
                label="Last Name"
              ></v-text-field>
              <v-text-field
                v-model="newTeacher.email"
                :rules="[(v) => /.+@.+\..+/.test(v) || 'E-mail must be valid']"
                label="Email"
              ></v-text-field>

              <v-text-field v-model="newTeacher.dob" label="DOB"></v-text-field>
              <v-text-field
                v-model="newTeacher.phone"
                label="Phone"
              ></v-text-field>
              <v-select
                v-model="newTeacher.gender"
                :items="
                  genderOptions
                    .map((option) => option.value)
                    .filter((gender) => gender !== '')
                "
                label="Gender"
              ></v-select>
              <v-text-field
                v-model="newTeacher.password"
                :rules="[(v) => !!v || 'Password is required']"
                label="Password"
                type="password"
              ></v-text-field>
              <v-text-field
                v-model="newTeacher.password_confirmation"
                :rules="[(v) => !!v || 'Confirm Password is required']"
                label="Confirm Password"
                type="password"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="blue-darken-1"
              variant="text"
              @click="closeCreateDialog"
              >Cancel</v-btn
            >
            <v-spacer></v-spacer>
            <v-btn color="green" @click="createItemConfirm">Create</v-btn>
          </v-card-actions>
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

    <template v-slot:item.actions="{ item }">
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
    </template>

  </v-data-table>

</template>
<script>
import axios from "@/axios";
export default {
  data: () => ({
    headers: [
      { title: "Name ", key: "fullname" },
      { title: "ID", key: "id" },
      { title: "Email", key: "email" },
      { title: "DOB", key: "dob" },
      { title: "Phone", key: "phone" },
      { title: "Gender", key: "gender" },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    dialogEdit: false,
    dialogDelete: false,
    dialogCreate: false,
    loading: true,
    teachers: [],
    search: "",
    selectedGender: "",
    genderOptions: [
      { title: "All", value: "" },
      { title: "Male", value: "male" },
      { title: "Female", value: "female" },
      { title: "Other", value: "other" },
    ],
    teacherToDelete: null,
    teacherToEdit: null,
    newTeacher: {
      first_name: "",
      last_name: "",
      email: "",
      dob: "",
      phone: "",
      gender: "",
      password: "",
      password_confirmation: "",
    },
    valid: false,
    showSuccessSnackbar: false,
    showErrorSnackbar: false,
    successMessage: "",
    errorMessage: "",
    timeout: 3000,
  }),

  created() {
    this.fetchData();
  },
  computed: {
    filteredTeachers() {
      return this.teachers.filter((teacher) => {
        const matchesGender = this.selectedGender
          ? teacher.gender === this.selectedGender
          : true;
        const matchesSearch =
          teacher.fullname.toLowerCase().includes(this.search.toLowerCase()) ||
          teacher.id.toString().includes(this.search);
        return matchesGender && matchesSearch;
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
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/teachers");
        this.teachers = response.data.map((teacher) => ({
          ...teacher,
          fullname: `${teacher.first_name} ${teacher.last_name}`,
        }));
        // console.log(this.teachers);
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
          await axios.delete(`/delete-teacher/${this.teacherToDelete.id}`);
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
      // this.teacherToEdit = null;
    },
    async editItemConfirm() {
      if (this.$refs.editForm.validate()) {
        try {
          this.loading=true
          await axios.put(
            `/update-teacher/${this.teacherToEdit.id}`,
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
        }finally {
        this.loading = false;
      }
      }
    },

    openCreateDialog() {
      this.dialogCreate = true;
    },

    closeCreateDialog() {
      this.dialogCreate = false;
      this.newTeacher = {
        first_name: "",
        last_name: "",
        email: "",
        dob: "",
        phone: "",
        gender: "",
        password: "",
      };
    },

    async createItemConfirm() {
      if (this.$refs.createForm.validate()) {
        try {
          const response = await axios.post(
            "/register-teacher",
            this.newTeacher
          );
          console.log(response);
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
  },
};
</script>
