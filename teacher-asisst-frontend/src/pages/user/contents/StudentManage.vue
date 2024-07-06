<template>
  <v-data-table
    :headers="headers"
    :items="filteredStudents"
    class="cursor-pointer"
    item-value="name"
    show-select
    :loading="loading"
  >
    <template v-slot:top>
      <v-card-title>students</v-card-title>

      <v-toolbar color="grey-lighten-5">
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
          color="#F3797E"
          variant="flat"
          @click="openCreateDialog"
        >
          Add students
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
            Are you sure you want to delete this Student?
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="blue-darken-1"
              variant="text"
              @click="closeDeleteDialog"
              > Cancel </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="red" @click="deleteItemConfirm">Delete</v-btn>
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
                <v-toolbar-title class="mb-10">Add student</v-toolbar-title>
                <v-row>
                  <v-select
                    v-model="selectedStudents"
                    :items="studentOptions"
                    label="Select students"
                    item-title="name"
                    item-value="id"
                    multiple
                    variant="outlined"
                  >
                    <template v-slot:prepend-item>
                      <v-list-item ripple @mousedown.prevent @click="toggle">
                        <v-list-item-action>
                          <v-icon
                            :color="
                              selectedStudents.length > 0
                                ? 'indigo darken-4'
                                : ''
                            "
                          >
                            {{ icon }}
                          </v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                          <v-list-item-title> Select All </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-divider class="mt-2"></v-divider>
                    </template>
                    <template v-slot:append-item>
                      <v-divider class="mb-2"></v-divider>
                      <v-list-item disabled>
                        <v-list-item-avatar color="grey lighten-3">
                          <v-icon> mdi-food-apple </v-icon>
                        </v-list-item-avatar>

                        <v-list-item-content v-if="likesAllStudents">
                          <v-list-item-title>
                            All students has been selected.
                          </v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-content v-else-if="likesSomeStudents">
                          <v-list-item-title> Student Count </v-list-item-title>
                          <v-list-item-subtitle>
                            {{ selectedStudents.length }}
                          </v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-content v-else>
                          <v-list-item-subtitle>
                            Go ahead, make a selection above!
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-select>
                </v-row>

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
                    @click="displaySelectedStudent"
                    >Add</v-btn
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

    <template v-slot:item.actions="{ item }">
      {{console.log(item)}}
      <div class="d-flex align-center justify-end">
        <v-chip
          color="blue"
          class="ma-2"
          :to="`/admin/profile/basic-info/${item.user_id}/personal-details`"
          >views</v-chip
        >
        <v-chip
          color="red"
          class="ma-2"
          @click="openDeleteDialog(item)"
          >Delete</v-chip
        >
        <!-- <v-icon color="red" size="small" @click="openDeleteDialog(item)">
          mdi-delete
        </v-icon> -->
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
      {
        title: "Action",
        value: "actions",
        filterable: false,
        sortable: false,
        align: "end",
      },
    ],

    dialogEdit: false,
    dialogDelete: false,
    dialogCreate: false,
    loading: true,
    students: [],
    search: "",
    selectedGender: "",
    flow: ["month", "year", "calendar"],
    genderOptions: [
      { title: "All", value: "" },
      { title: "Male", value: "male" },
      { title: "Female", value: "female" },
      { title: "Other", value: "other" },
    ],
    studentLists: [],
    selectedStudents: [],
    studentIdToDelete: [],
    studentToEdit: null,
    newStudent: {
      first_name: "",
      last_name: "",
      email: "",
      dob: new Date().toISOString().substr(0, 10),
      phone: "",
      gender: "",
      password: "",
      password_confirmation: "",
    },
    student_ids: [],
    displayStudents: [],
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
    this.fetchStudentsFromClassId();
  },
  computed: {
    filteredStudents() {
      return this.students.filter((student) => {
        const matchesGender = this.selectedGender
          ? student.user.profile.gender === this.selectedGender
          : true;

        const matchesSearch =
          student.fullname.toLowerCase().includes(this.search.toLowerCase()) ||
          student.id.toString().includes(this.search);
        return matchesGender && matchesSearch;
      });
    },
    studentOptions() {
      return this.studentLists.map((student) => ({
        id: student.id,
        name: student.user.email,
      }));
    },
    likesAllStudents() {
      return this.selectedStudents.length === this.studentOptions.length;
    },
    likesSomeStudents() {
      return this.selectedStudents.length > 0 && !this.likesAllStudents;
    },
    icon() {
      if (this.likesAllStudents) return "mdi-close-box";
      if (this.likesSomeStudents) return "mdi-minus-box";
      return "mdi-checkbox-blank-outline";
    },
  },
  mounted() {
    this.registeredStudents();
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
    selectedStudents(val) {
      this.student_ids = val;
    },
  },
  methods: {
    toggle() {
      this.$nextTick(() => {
        if (this.likesAllStudents) {
          this.selectedStudents = [];
        } else {
          this.selectedStudents = this.studentOptions.map(
            (student) => student.id
          );
        }
      });
    },

    async fetchStudentsFromClassId() {
      try {
        this.loading = true;
        const response = await axios.get(`classes/${this.$route.params.id}`);
        this.students = response.data.students.map((student) => ({
          ...student,
          fullname: student.user.name,
          email: student.user.email,
          dob: student.user.profile.dob || "N/A", // Handle null value for DOB
          phone: student.user.profile.phone || "N/A", // Handle null value for phone
          gender: student.user.profile.gender || "N/A",
        }));
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      } finally {
        this.loading = false;
      }
    },
    async registeredStudents() {
      try {
        this.loading = true;
        const response = await axios.get("students");
        this.studentLists = response.data;
      } catch (error) {
        console.error("Error fetching student list:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      } finally {
        this.loading = false;
      }
    },



    closeDeleteDialog() {
      this.dialogDelete = false;
      this.studentIdToDelete = null;
    },


    openDeleteDialog(item) {
      const ItemID=[];
      ItemID.push(item.id);
      this.student_ids=ItemID;
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      console.log(this.student_ids);
        try {
          await axios.delete(`classes/${this.$route.params.id}/detach`,{student_ids:this.student_ids},);
          this.closeDeleteDialog();
          this.fetchStudentsFromClassId(); //
          this.showSuccessSnackbar = true;
          this.successMessage = "Student deleted successfully.";
        } catch (error) {
          console.error("Error deleting data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error deleting Student.";
        }
    },

    openEditDialog(item) {
      this.studentToEdit = { ...item };
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
            `/students/${this.studentToEdit.id}/edit`,
            this.studentToEdit
          );
          this.closeEditDialog();
          this.registeredStudents();
          this.showSuccessSnackbar = true;
          this.successMessage = "Student edited successfully.";
        } catch (error) {
          console.error("Error updating data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error editing Student.";
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
    displaySelectedStudent() {
      this.student_ids = this.selectedStudents;
      // console.log(this.student_ids);
      this.createItemConfirm();
    },
    async createItemConfirm() {
      if (this.$refs.createForm.validate()) {
        try {
          this.IsLoading = true;
          await axios.post(`classes/${this.$route.params.id}/attach`, {
            student_ids: this.student_ids,
          });
          this.closeCreateDialog();
          this.fetchStudentsFromClassId(); // Refresh the student list in the class
          this.showSuccessSnackbar = true;
          this.successMessage = "Students added successfully.";
        } catch (error) {
          console.error("Error creating data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error adding students.";
        } finally {
          this.IsLoading = false;
        }
      }
    },
  },

  generatePassword() {
    const length = 12;
    const charset =
      "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
    let generatedPassword = "";
    for (let i = 0, n = charset.length; i < length; ++i) {
      generatedPassword += charset.charAt(Math.floor(Math.random() * n));
    }
    this.newStudent.password = generatedPassword;
    this.newStudent.password_confirmation = generatedPassword;
  },

  //validation rules

  required: (value) => !!value || "Required.",
  counter: (value) => value.length >= 3 || "min 3 characters",
  isEmail: (value) => {
    const pattern =
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(value) || "Invalid e-mail.";
  },
};
</script>
