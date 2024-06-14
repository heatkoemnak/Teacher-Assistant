<template>
  <div>
    <v-card>
      <v-card-title>Subjects</v-card-title>
      <v-data-table
        :headers="subjectHeaders"
        :items="filteredSubjects"
        class="elevation-1 cursor-pointer"
        item-value="name"
        show-select
        :loading="loading"
        height="450"
      >
        <template v-slot:top>
          <v-toolbar flat>
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
            <v-btn color="primary" dark class="mb-2" @click="openCreateDialog"
              >New Subject</v-btn
            >
          </v-toolbar>
        </template>

        <template v-slot:item.actions="{ item }">
          <div class="d-flex justify-end">
            <v-icon small color='blue' class="mr-2" @click="editSubject(item)"
              >mdi-pencil</v-icon
            >
            <v-icon small color='red' @click="deleteSubject(item)">mdi-delete</v-icon>
          </div>
        </template>
      </v-data-table>
    </v-card>

    <!-- Create Dialog -->
    <v-dialog v-model="dialogCreate" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">New Subject</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="createForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Subject Name"
              v-model="newSubject.name"
              :rules="[required]"
              required
            ></v-text-field>
            <v-select
              :items="depOptions"
              label="Department"
              v-model="newSubject.department_id"
              :rules="[required]"
              required
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeCreateDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="addSubject">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit Dialog -->
    <v-dialog v-model="dialogEdit" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Edit Subject</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="editForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Subject Name"
              v-model="subjectToEdit.name"
              :rules="[required]"
              required
            ></v-text-field>
            <v-select
              :items="depOptions"
              label="Department"
              v-model="subjectToEdit.department_id"
              :rules="[required]"
              required
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeEditDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="editSubjectConfirm"
            >Save</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Delete Dialog -->
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Confirm Delete</span>
        </v-card-title>
        <v-card-text>Are you sure you want to delete this subject?</v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeDeleteDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="red" text @click="deleteSubjectConfirm">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      subjects: [],
      departments: [],
      subjectHeaders: [
        { title: "Subject Name", value: "name" },
        { title: "Department", value: "department" },
        { title: "Actions", value: "actions", sortable: false,align:"end" },
      ],
      loading: true,
      search: "",
      dialogCreate: false,
      dialogEdit: false,
      dialogDelete: false,
      depOptions:[],
      valid: false,
      newSubject: {
        name: "",
        department_id: "",
      },
      subjectToEdit: null,
      required: (value) => !!value || "Required.",
    };
  },
  computed: {
    filteredSubjects() {
      return this.subjects
        .map((subject) => ({
          ...subject,
          department: this.getDepartmentName(subject.department_id),
        }))
        .filter((subject) =>
          subject.name.toLowerCase().includes(this.search.toLowerCase())
        );
    },
  },
  methods: {
    getDepartmentName(department_id) {
      const department = this.departments.find(
        (dep) => dep.id === department_id
      );
      return department ? department.name : "";
    },
    fetchSubjects() {
      axios
        .get("/subjects")
        .then((response) => {
          this.subjects = response.data;
          this.loading = false;
        })
        .catch((error) => {
          console.error("Error fetching subjects:", error);
          this.loading = false;
        });
    },
    fetchDepartments() {
      axios
        .get("/deps")
        .then((response) => {
          this.departments = response.data;
          this.depOptions = [
            { title: "All", value: "" },
            ...this.departments.map((dep) => ({ title: dep.name, value: dep.id })),
          ];
        })
        .catch((error) => {
          console.error("Error fetching departments:", error);
        });
    },
    openCreateDialog() {
      this.dialogCreate = true;
    },
    closeCreateDialog() {
      this.dialogCreate = false;
    },
    addSubject() {
      if (this.$refs.createForm.validate()) {
        axios
          .post("/subjects/create", this.newSubject)
          .then(() => {
            this.fetchSubjects();
            this.newSubject.name = "";
            this.newSubject.department_id = "";
            this.dialogCreate = false;
          })
          .catch((error) => {
            console.error("Error adding subject:", error);
          });
      }
    },
    editSubject(item) {
      this.subjectToEdit = { ...item };
      this.dialogEdit = true;
    },
    closeEditDialog() {
      this.dialogEdit = false;
      this.resetForm(this.$refs.editForm);
    },
    editSubjectConfirm() {
      if (this.$refs.editForm.validate()) {
        axios
          .put(`/subjects/${this.subjectToEdit.id}`, this.subjectToEdit)
          .then(() => {
            this.fetchSubjects();
            this.dialogEdit = false;
          })
          .catch((error) => {
            console.error("Error editing subject:", error);
          });
      }
    },
    deleteSubject(item) {
      this.subjectToDelete = item;
      this.dialogDelete = true;
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
    },
    deleteSubjectConfirm() {
      axios
        .delete(`/subjects/${this.subjectToDelete.id}/delete`)
        .then(() => {
          this.fetchSubjects();
          this.dialogDelete = false;
        })
        .catch((error) => {
          console.error("Error deleting subject:", error);
        });
    },
    resetForm(form) {
      if (form) {
        form.reset();
        form.resetValidation();
      }
    },
  },
  mounted() {
    this.fetchSubjects();
    this.fetchDepartments();
  },
};
</script>

<style scoped>
.v-data-table-header {
  background-color: #f5f5f5;
}
</style>
