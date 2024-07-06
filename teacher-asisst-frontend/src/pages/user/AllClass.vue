<template>
  <layout>
    <v-col cols="12" class="d-flex justify-space-between align-center">
      <span class="px-5 rounded-lg">All Classes</span>
      <v-spacer></v-spacer>
      <v-text-field
        :loading="loading"
        append-inner-icon="mdi-magnify"
        density="compact"
        v-model="searchText"
        label="Search class"
        variant="outlined"
        single-line
        @click:append-inner="onClick"
        @input="filterClasses"
      ></v-text-field>
    </v-col>
    <template v-if="filteredClasses.length">
      <v-row align="center" justify="start" dense>
        <v-col
          cols="12"
          md="3"
          v-for="classItem in filteredClasses"
          :key="classItem.id"
        >
            <v-card
              class="mx-auto cursor-pointer"
              :subtitle="classItem.desc"
              :title="classItem.slug"
              
            >
              <div class="pa-5 d-flex">
                <v-avatar color="red">
                  <span class="text-h5 pa-5">{{ classItem.name }}</span>
                </v-avatar>
                
                <v-spacer></v-spacer>
                <v-menu>
                  <template v-slot:activator="{ props }">
                    <v-btn variant="flate" icon="mdi-dots-vertical" v-bind="props"></v-btn>
                  </template>
                  <v-list>
                    <v-list-item @click="editClass(classItem.id)">
                      <v-list-item-title>Edit</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="deleteClass(classItem.id)">
                      <v-list-item-title>Delete</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>
              <template v-slot:append>
                <v-btn variant="text":to="{ name: 'class', params: { slug: classItem.slug,id:classItem.id } }">view class</v-btn>
              </template>
            </v-card>
            
        </v-col>
      </v-row>
    </template>
    <template v-else>
      <v-main>
        <v-empty-state
          image="https://vuetifyjs.b-cdn.net/docs/images/components/v-empty-state/astro-dog.svg"
          size="100"
          text-width="150"
        >
          <template v-slot:media>
            <v-img class="mb-8"></v-img>
          </template>
          <template v-slot:title>
            <v-progress-linear
              color="deep-purple-accent-4"
              height="6"
              indeterminate
              rounded
              v-if="loading"
            ></v-progress-linear>
            <div v-else class="text-h6 text-high-emphasis">
              No classes found
            </div>
          </template>
          <template v-slot:text>
            <div class="text-body-1">No classes available at the moment.</div>
          </template>
        </v-empty-state>
      </v-main>
    </template>
    <div class="text-center pa-4">
      <v-dialog
        v-model="dialog"
        transition="dialog-bottom-transition"
        width="700px"
      >
        <template v-slot:activator="{ props: activatorProps }">
          <v-layout-item
            class="text-end"
            position="bottom"
            size="80"
            model-value
          >
            <v-btn
              class="ma-4"
              elevation="8"
              color="#4c00d5"
              icon="mdi-plus"
              v-bind="activatorProps"
              ><v-tooltip activator="parent" location="top"
                ><template v-slot:activator="{ props }">
                  <v-btn icon v-bind="props" color="#4c00d5" >
                    <v-icon> mdi-plus </v-icon>
                  </v-btn>
                </template>
                <span>Create</span></v-tooltip
              ></v-btn
            >
          </v-layout-item>
        </template>

        <v-card>
          <v-card-title>
            <span class="text-h5">Class Info</span>
          </v-card-title>
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field
                v-model="classData.name"
                label="Class name"
                :rules="[(v) => !!v || 'Name is required']"
                required
                variant="outlined"
              ></v-text-field>
              <v-text-field
                v-model="classData.slug"
                label="Slug"
                :rules="[(v) => !!v || 'Slug is required']"
                required
                variant="outlined"
              ></v-text-field>
              <v-textarea
                v-model="classData.desc"
                label="Class description * (Optional)"
                variant="outlined"
              ></v-textarea>
              <v-file-input
                v-model="classData.image"
                label="image icon"
                accept="image/*"
                variant="outlined"
              ></v-file-input>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false"
              >Cancel</v-btn
            >
            <v-btn color="blue darken-1" text @click="handleCreateClass">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </layout>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      dialog: false,
      notifications: false,
      sound: true,
      widgets: false,
      searchText: "",
      classes: [],
      teacher: "",
      recentClasses: [],
      loading: false,
      valid: false,
      isEditMode: false,
      classData: {
        id: null,
        name: "",
        slug: "",
        desc: "",
        teacher_id: "",
        image: null,
      },
    };
  },

  computed: {
    filteredClasses() {
      const searchText = this.searchText.trim().toLowerCase();
      return this.classes.filter((classItem) =>
        classItem.name.toLowerCase().includes(searchText)
      );
    },
    dialogTitle() {
      return this.isEditMode ? "Edit Class" : "Create Class";
    },
  },

  mounted() {
    this.fetchTeacher();
  },

  methods: {
    async handleCreateClass() {
      try {
        if (this.$refs.form.validate()) {
          if (this.isEditMode) {
            await axios.put(`classes/${this.classData.id}`, this.classData);
          } else {
            await axios.post("classes/create", this.classData);
          }
          this.dialog = false;
          this.fetchClasses(this.classData.teacher_id);
          this.resetForm();
        }
      } catch (error) {
        console.error(error);
      }
    },

    async fetchTeacher() {
      try {
        const response = await axios.get(`teachers/${this.$store.state.user.id}/user`);
        const data = response.data;
        this.teacher = data.teacher;
        this.classData.teacher_id = data.teacher.id;
        this.fetchClasses(this.teacher.id);
      } catch (error) {
        console.error(error);
      }
    },

    async fetchClasses(teacherId) {
      try {
        const response = await axios.get(`classes/${teacherId}/classes`);
        this.classes = response.data;
      } catch (error) {
        console.error(error);
      }
    },

    resetForm() {
      this.classData = {
        id: null,
        name: "",
        slug: "",
        desc: "",
        teacher_id: this.teacher.id,
        image: null,
      };
      this.valid = false;
      this.isEditMode = false;
    },

    openCreateDialog() {
      this.resetForm();
      this.dialog = true;
    },

    async editClass(classId) {
      try {
        const response = await axios.get(`classes/${classId}`);
        const classData = response.data;
        this.classData = {
          id: classData.id,
          name: classData.name,
          slug: classData.slug,
          desc: classData.desc,
          teacher_id: classData.teacher_id,
          image: classData.image,
        };
        this.isEditMode = true;
        this.dialog = true;
      } catch (error) {
        console.error(error);
      }
    },

    async deleteClass(classId) {
      try {
        const confirmDelete = confirm("Are you sure you want to delete this class?");
        if (confirmDelete) {
          await axios.delete(`classes/${classId}`);
          this.fetchClasses(this.teacher.id); // Refresh the class list
        }
      } catch (error) {
        console.error(error);
      }
    },

    saveClass() {
      this.handleCreateClass();
    },
  },
};
</script>
