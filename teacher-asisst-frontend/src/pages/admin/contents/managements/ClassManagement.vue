<template>
  <v-data-table
    :headers="headers"
    :items="filteredClasses"
    class="elevation-1 cursor-pointer"
    item-value="name"
    show-select
    :loading="loading"
    height="450"
  >
    <template v-slot:top>
      <v-toolbar-title class="ma-4">Classes</v-toolbar-title>
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
          v-model="selectedDep"
          :items="DepOptions"
          label="Filter by Department"
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
          New Class
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
            Are you sure you want to delete this class?
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="blue-darken-1"
              variant="text"
              @click="closeDeleteDialog"
            >Cancel</v-btn>
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
              :active="IsLoadingUpdate"
              :indeterminate="IsLoadingUpdate"
              color="deep-purple-accent-4"
              absolute
              bottom
            ></v-progress-linear>
            <v-form ref="editForm" v-model="valid" lazy-validation>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="classToEdit.name"
                    :rules="[required]"
                    label="Class Name"
                    density="compact"
                  ></v-text-field>
                </v-col>

                <v-col cols="6">
                  <v-select
                    v-model="classToEdit.department_id"
                    :items="DepOptions"
                    label="Department"
                    density="compact"
                  ></v-select>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue-darken-1" variant="text" @click="closeEditDialog"
            >Cancel</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="green" @click="editItemConfirm">Edit</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialogCreate" max-width="700px">
        <v-card>
          <v-card-text>
            <v-form ref="createForm" v-model="valid" lazy-validation>
              <v-container>
                <v-toolbar-title class="mb-10">Create New Class</v-toolbar-title>
                <v-row>
                  <v-col cols="6">
                    <v-text-field
                      v-model="newClass.name"
                      :rules="[required, counter]"
                      label="Class Name"
                      density="compact"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="6">
                    <v-select
                      v-model="newClass.department_id"
                      :items="DepOptions"
                      label="Department"
                      density="compact"
                    ></v-select>
                    <v-select
                      v-model="newClass.teacher"
                      :items="FilteredTeacherOptions"
                      label="Teacher"
                      density="compact"
                    ></v-select>
                  </v-col>
                </v-row>

                <v-card-actions>
                  <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="closeCreateDialog"
                  >Cancel</v-btn>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="green"
                    :disabled="!valid"
                    class="mr-4"
                    @click="createItemConfirm"
                  >Create</v-btn>
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
      <div class="d-flex align-center justify-end">
        <v-chip color="blue" class="ma-2" :to="`/admin/profile/basic-info/${item.user_id}/personal-details`">View</v-chip>
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
      { title: "Name", value: "name" },
      { title: "ID", value: "id" },
      { title: "Department", value: "department_id" },
      { title: "Created at", value: "created_at" },
      { title: "Actions", value: "actions", filterable: false, sortable: false, align: "end" },
    ],
    dialogEdit: false,
    dialogDelete: false,
    dialogCreate: false,
    loading: true,
    classes: [],
    search: "",
    deps: [],
    selectedDep: "",
    DepOptions: [],
    TeacherOptions: [], // Add this
    classToDelete: null,
    classToEdit: null,
    newClass: {
      name: "",
      department_id: "",
      teacher: "", // Add this
    },
    valid: false,
    IsLoadingUpdate: false,
    showSuccessSnackbar: false,
    showErrorSnackbar: false,
    successMessage: "",
    errorMessage: "",
    timeout: 3000,
    required: (value) => !!value || "Required.",
    counter: (value) => value.length >= 3 || "min 3 characters",
  }),

  created() {
    this.fetchData();
    this.fetchDepartment();
    this.fetchTeachers(); // Add this
  },

  computed: {
    filteredClasses() {
      return this.classes.filter((cls) => {
        const matchesSearch =
          cls.name.toLowerCase().includes(this.search.toLowerCase())

        const matchesDep =
          this.selectedDep === "" || cls.department_id === this.selectedDep;

        return matchesSearch && matchesDep;
      });
    },
    FilteredTeacherOptions() {
      return this.TeacherOptions.filter(
        (teacher) => teacher.department_id === this.newClass.department_id
      );
    },
  },

  methods: {
    async fetchData() {
      try {
        this.loading = true;
        const response = await axios.get("/classes");
        this.classes = response.data.map((item) => ({
          ...item,
          created_at: item.created_at || "N/A", // Handle null value for phone
          department_id: item.department_id || "N/A",
        }));
        console.log(this.classes);
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      } finally {
        this.loading = false;
      }
    },
    async fetchDepartment() {
      try {
        const response = await axios.get("/deps");
        this.deps = response.data;
        this.DepOptions = [{ title: "All", value: "" }, ...this.deps.map(dep => ({ title: dep.name, value: dep.id }))];
      } catch (error) {
        console.error("Error fetching departments:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching departments.";
      }
    },
    async fetchTeachers() {
      try {
        const response = await axios.get("/teachers");
        this.TeacherOptions = response.data.map(teacher => ({ title: teacher.name, value: teacher.id, department_id: teacher.department_id }));
      } catch (error) {
        console.error("Error fetching teachers:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching teachers.";
      }
    },
    openCreateDialog() {
      this.dialogCreate = true;
    },
    closeCreateDialog() {
      this.dialogCreate = false;
      this.resetNewClass();
    },
    openEditDialog(item) {
      this.classToEdit = { ...item };
      this.dialogEdit = true;
    },
    closeEditDialog() {
      this.dialogEdit = false;
      // this.classToEdit = null;
    },
    openDeleteDialog(item) {
      this.classToDelete = item;
      this.dialogDelete = true;
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
      this.classToDelete = null;
    },
    async createItemConfirm() {
      if (this.$refs.createForm.validate()) {
        try {
          const response = await axios.post("/classes", this.newClass);
          this.classes.push(response.data);
          this.showSuccessSnackbar = true;
          this.successMessage = "Class created successfully!";
          this.closeCreateDialog();
        } catch (error) {
          console.error("Error creating class:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error creating class.";
        }
      }
    },
    async editItemConfirm() {
      if (this.$refs.editForm.validate()) {
        this.IsLoadingUpdate = true;
        try {
          await axios.put(`/classes/${this.classToEdit.id}`, this.classToEdit);
          const index = this.classes.findIndex((cls) => cls.id === this.classToEdit.id);
          if (index !== -1) {
            this.classes.splice(index, 1, this.classToEdit);
          }
          this.showSuccessSnackbar = true;
          this.successMessage = "Class updated successfully!";
          this.closeEditDialog();
        } catch (error) {
          console.error("Error updating class:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error updating class.";
        } finally {
          this.IsLoadingUpdate = false;
        }
      }
    },
    async deleteItemConfirm() {
      try {
        await axios.delete(`/classes/${this.classToDelete.id}`);
        const index = this.classes.findIndex((cls) => cls.id === this.classToDelete.id);
        if (index !== -1) {
          this.classes.splice(index, 1);
        }
        this.showSuccessSnackbar = true;
        this.successMessage = "Class deleted successfully!";
        this.closeDeleteDialog();
      } catch (error) {
        console.error("Error deleting class:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error deleting class.";
      }
    },
    resetNewClass() {
      this.newClass = {
        name: "",
        id: "",
        department_id: "",
        teacher: "", // Add this
      };
    },
  },
};
</script>

<style scoped>
</style>
