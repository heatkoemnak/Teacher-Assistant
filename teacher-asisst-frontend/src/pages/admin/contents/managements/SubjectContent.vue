<template>
  <v-container>
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
            <v-toolbar-title>Subjects</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" dark class="mb-2" @click="openCreateDialog">New Subject</v-btn>
          </v-toolbar>

          <v-text-field
            v-model="search"
            label="Search by Subject Name"
            prepend-inner-icon="mdi-magnify"
            class="mx-4"
            hide-details
          ></v-text-field>
        </template>

        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editSubject(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteSubject(item)">mdi-delete</v-icon>
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
            <v-text-field label="Subject Name" v-model="newSubject.name" :rules="[required]" required></v-text-field>
            <v-select
              :items="departments"
              item-text="name"
              item-value="id"
              label="Department"
              v-model="newSubject.department_id"
              :rules="[required]"
              required
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeCreateDialog">Cancel</v-btn>
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
            <v-text-field label="Subject Name" v-model="subjectToEdit.name" :rules="[required]" required></v-text-field>
            <v-select
              :items="departments"
              item-text="name"
              item-value="id"
              label="Department"
              v-model="subjectToEdit.department_id"
              :rules="[required]"
              required
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeEditDialog">Cancel</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="editSubjectConfirm">Save</v-btn>
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
          <v-btn color="blue darken-1" text @click="closeDeleteDialog">Cancel</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="red" text @click="deleteSubjectConfirm">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from '@/axios';

export default {
  data() {
    return {
      subjects: [],
      departments: [],
      subjectHeaders: [
        { text: 'Subject Name', value: 'name' },
        { text: 'Department', value: 'department' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      loading: true,
      search: '',
      dialogCreate: false,
      dialogEdit: false,
      dialogDelete: false,
      valid: false,
      newSubject: {
        name: '',
        department_id: null,
      },
      subjectToEdit: null,
      required: value => !!value || 'Required.',
    };
  },
  computed: {
    filteredSubjects() {
      return this.subjects.map(subject => ({
        ...subject,
        department: this.getDepartmentName(subject.department_id),
      })).filter(subject =>
        subject.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  methods: {
    getDepartmentName(department_id) {
      const department = this.departments.find(dep => dep.id === department_id);
      return department ? department.name : '';
    },
    fetchSubjects() {
      axios.get('/subjects')
        .then(response => {
          this.subjects = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error fetching subjects:', error);
          this.loading = false;
        });
    },
    fetchDepartments() {
      axios.get('/deps')
        .then(response => {
          this.departments = response.data;
        })
        .catch(error => {
          console.error('Error fetching departments:', error);
        });
    },
    openCreateDialog() {
      this.dialogCreate = true;
    },
    closeCreateDialog() {
      this.dialogCreate = false;
      this.resetForm(this.$refs.createForm);
    },
    addSubject() {
      if (this.$refs.createForm.validate()) {
        axios.post('/subjects', this.newSubject)
          .then(() => {
            this.fetchSubjects();
            this.newSubject.name = '';
            this.newSubject.department_id = null;
            this.dialogCreate = false;
          })
          .catch(error => {
            console.error('Error adding subject:', error);
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
        axios.put(`/subjects/${this.subjectToEdit.id}`, this.subjectToEdit)
          .then(() => {
            this.fetchSubjects();
            this.dialogEdit = false;
          })
          .catch(error => {
            console.error('Error editing subject:', error);
          });
      }
    },
    deleteSubject(item) {
      this.subjectToEdit = item;
      this.dialogDelete = true;
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
    },
    deleteSubjectConfirm() {
      axios.delete(`/subjects/${this.subjectToEdit.id}`)
        .then(() => {
          this.fetchSubjects();
          this.dialogDelete = false;
        })
        .catch(error => {
          console.error('Error deleting subject:', error);
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
