<template>
  <v-data-table-server
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
    fixed-header
    height="420"
    disable-pagination
    disable-sort
    hide-default-footer
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Manage Teachers</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ props }">
            <v-text-field
              v-model="search"
              class="ma-2 hidden-sm-and-down"
              density="compact"
              placeholder="Search by name, email, or teacher ID..."
              hide-details
              width="20"
              variant="outlined"
              prepend-inner-icon="mdi-magnify"
            ></v-text-field>
            <v-btn
              v-bind="props"
              color="white"
              class="bg-primary ml-2 white--text"
            >
            Export CSV
            </v-btn>
            <v-btn
              v-bind="props"
              color="white"
              class="bg-red ml-2 white--text"
            >
            Import CSV
            </v-btn>
            <v-btn
              v-bind="props"
              color="white"
              class="bg-primary ml-2 white--text"
            >
              <v-icon dark>mdi-plus</v-icon>AddNew
            </v-btn>
          </template>

          <v-card>
            <v-toolbar
              dense
              flat
              class="body-2 font-weight-bold px-5"
              color="red lighten-2"
            >
              {{ formTitle }}
            </v-toolbar>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-card-text>
                <v-row>
                  <v-col cols="12" md="6" class="mb-0">
                    <v-text-field
                      density="compact"
                      label="Full Name"
                      v-model="editedItem.name"
                      :rules="requiredRules"
                      prepend-inner-icon="mdi-account-arrow-right-outline"
                      variant="outlined"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6" class="mb-0">
                    <v-text-field
                      density="compact"
                      label="ID"
                      v-model="editedItem.student_id"
                      :rules="requiredRules"
                      prepend-inner-icon="mdi-account-arrow-right-outline"
                      variant="outlined"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      density="compact"
                      label="E-Mail"
                      v-model="editedItem.email"
                      variant="outlined"
                      :rules="emailRules"
                      prepend-inner-icon="mdi-email-outline"
                    />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field
                      density="compact"
                      label="Phone Number"
                      v-model="editedItem.phone"
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
                      v-model="editedItem.gender"
                      variant="outlined"
                      :rules="requiredRules"
                      />
                  </v-col>

                </v-row>
              </v-card-text>
            </v-form>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancel
              </v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="validate">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
            <v-row class="ma-1">
              <v-col cols="12"
                >Are you sure you want to delete this item?</v-col
              >
              <v-card-actions>
                <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                  >Cancel</v-btn
                >
                <v-spacer></v-spacer>
                <v-btn color="red" @click="deleteItemConfirm"> Delete </v-btn>
              </v-card-actions>
            </v-row>
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
    <template v-slot:no-data> Loading... </template>
  </v-data-table-server>
</template>

<script>

import fakeDataAPI from "../../fakedata";
export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: "No.", key: "id" },
      { title: "Name", key: "name" },
      { title: "Student.ID", key: "student_id" },
      { title: "Email", key: "email" },
      { title: "Department", key: "department_id" },
      { title: "Class", key: "class_id" },
      { title: "Date Of Birth", key: "date_of_birth" },
      { title: "Gender", key: "gender" },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    select: ["Read", "Update"],
    items: ["Create", "Read", "Update", "Delete", "Vuetify"],
    serverItems: [],
    types: ["Admin", "User"],
    editedIndex: -1,
    editedItem: {
      id: 0,
      name: "",
      type: "",
    },
    defaultItem: {
      name: "",
      type: "",
    },
    loading: true,
    totalItems: 0,
    search: "",
    valid: false,
    formLoading: false,
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
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Student" : "Edit Student";
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
    search() {
      this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage });
    },
  },
  created() {
    this.loadItems({
      page: 1,
      itemsPerPage: 10,
      sortBy: [],
      sortDesc: [],
      search: "",
    });
  },
  methods: {
    async loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      try {
        const response = await fakeDataAPI.get("/api/students", {
          params: {
            page,
            itemsPerPage,
            sortBy,
            search: this.search,
          },
        });
        this.serverItems = response.data.items;
        this.totalItems = response.data.total;
      } catch (error) {
        alert(error);
      } finally {
        this.loading = false;
      }
    },

    editItem(item) {
      this.editedIndex = this.serverItems.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.serverItems.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.serverItems.splice(this.editedIndex, 1);
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
        Object.assign(this.serverItems[this.editedIndex], this.editedItem);
      } else {
        this.editedItem.id = this.serverItems.length + 1;
        this.serverItems.push(this.editedItem);
      }
      this.close();
    },
    validate() {
      if (this.$refs.form.validate()) {
        this.save();
        this.formLoading = true;
        // Timeout Function only for showing loading progress
        setTimeout(() => {
          this.formLoading = false;
          this.reset();
        }, 4000);
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
