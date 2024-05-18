<template>
  <v-data-table-server
    :headers="headers"
    :items="teachers"
    :sort-by="[{ key: 'id', order: 'asc' }]"
    height="420"
    disable-pagination
    disable-sort
    hide-default-footer
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Manage User</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2" color="primary" dark v-bind="props">
              Add New Teacher
            </v-btn>
            {{ console.log(teachers) }}
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" md="6" sm="6">
                    <v-text-field
                      v-model="editedItem.name"
                      label="User Name"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6" sm="6">
                    <v-text-field
                      v-model="editedItem.email"
                      label="User Email"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancel
              </v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="save">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5"
              >Are you sure you want to delete this item?</v-card-title
            >
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                >Cancel</v-btn
              >
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="deleteItemConfirm"
                >OK</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon color="blue" class="me-2" size="small" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon color="red" size="small" @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-dialog
      v-model="dialog"
      max-width="320"
      persistent
    >
      <v-list
        class="py-2"
        color="primary"
        elevation="12"
        rounded="lg"
      >
        <v-list-item
          prepend-icon="$vuetify-outline"
          title="Refreshing Application..."
        >
          <template v-slot:prepend>
            <div class="pe-4">
              <v-icon color="primary" size="x-large"></v-icon>
            </div>
          </template>

          <template v-slot:append>
            <v-progress-circular
              color="primary"
              indeterminate="disable-shrink"
              size="16"
              width="2"
            ></v-progress-circular>
          </template>
        </v-list-item>
      </v-list>
    </v-dialog>
    </template>
  </v-data-table-server>
</template>
<script>
import fakeDataAPI from "@/pages/admin/fakedata";

export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: "No.", key: "id" },
      { title: "Name", key: "name" },
      { title: "Teacher.ID", key: "teacher_id" },
      {
        title: "Email",
        key: "email",
      },
      {
        title: "Subjects",
        key: "subject",
      },
      { title: "Date Of Birth", key: "date_of_birth" },
      { title: "Gender", key: "gender" },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    // headers: [
    //   {
    //     title: "No.",
    //     align: "start",
    //     sortable: false,
    //     key: "id",
    //   },
    //   { title: "User Name", key: "name" },
    //   { title: "User Type", key: "type" },
    //   { title: "Permissions", key: "permissions" },
    //   { title: "Accounts", key: "account" },
    //   { title: "Actions", key: "actions", sortable: false },
    // ],
    select: ["Read", "Update"],
    items: ["Create", "Read", "Update", "Delete", "Vuetify"],
    desserts: [],
    types: ["Admin", "User"],
    editedIndex: -1,
    teachers: [],
    editedItem: {
      id: 0,
      name: "",
      email: "",
    },
    defaultItem: {
      name: "",
      email: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Teacher" : "Edit Teacher";
    },
  },

  watch: {
    // dialog(val) {
    //   val || this.close();
    // },
    dialogDelete(val) {
      val || this.closeDelete();
    },
    dialog (val) {
        if (!val) return
        setTimeout(() => (this.dialog = false), 4000)
        val || this.close();
      },
  },
  async mounted() {
    try {
      const teacherResponse = await fakeDataAPI.get("/api/teachers");
      this.teachers = teacherResponse.data;

      // const studentResponse = await fakeDataAPI.get("/api/students");
      // this.students = studentResponse.data;

      // const departmentResponse = await fakeDataAPI.get("/api/departments");
      // this.departments = departmentResponse.data;
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  },

  // created() {
  //   this.initialize();
  // },

  methods: {
    // initialize() {
    //   this.teachers;
    // },
    // initialize() {
    //   this.desserts = [
    //     {
    //       id: 1,
    //       name: "Frozen Yogurt",
    //       type: "User",
    //     },
    //     {
    //       id: 2,
    //       name: "Ice cream sandwich",
    //       type: "User",
    //     },
    //     {
    //       id: 3,
    //       name: "Eclair",
    //       type: "Admin",
    //     },
    //     {
    //       id: 4,
    //       name: "Cupcake",
    //       type: "User",
    //     },
    //     {
    //       id: 5,
    //       name: "Gingerbread",
    //       type: "Admin",
    //     },
    //     {
    //       id: 6,
    //       name: "Jelly bean",
    //       type: "User",
    //     },
    //   ];
    // },

    editItem(item) {
      console.log(item);
      // this.dessert += 1;
      this.editedIndex = this.teachers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.teachers.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.teachers.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.teachers[this.editedIndex], this.editedItem);
      } else {
        this.editedItem.id = this.teachers.length + 1;
        console.log(this.dessert);
        this.teachers.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>
