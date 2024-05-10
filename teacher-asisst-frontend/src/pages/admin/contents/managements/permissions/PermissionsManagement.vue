<template>
  <v-data-table
    :headers="headers"
    :items="permissions"
    :sort-by="[{ key: 'id', order: 'asc' }]"
    height="420"
    disable-pagination
    disable-sort
    hide-default-footer
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Manage Permissions</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2" color="primary" dark v-bind="props">
              Add New Permission
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="title-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="editedItem.name" label="Permission Name"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="editedItem.type" label="Permission Type"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" variant="title" @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" variant="title" @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="title-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" variant="title" @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" variant="title" @click="deleteItemConfirm">OK</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon class="me-2" size="small" @click="editItem(item)">mdi-pencil</v-icon>
      <v-icon size="small" @click="deleteItem(item)">mdi-delete</v-icon>
    </template>
    <template v-slot:item.checkbox="{ item }">
      <v-checkbox v-model="selectedItems" :value="item.id"></v-checkbox>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>
</template>


<script>
export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      { title: "ID", value: "id", align: "start", sortable: true },
      { title: "Permission Name", value: "name" },
      { title: "Permission Type", value: "type" },
      { title: "Actions", value: "actions", sortable: false },
      { title: "Select", value: "checkbox", sortable: false },
    ],
    permissions: [],
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
    selectedItems: [],
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Permission" : "Edit Permission";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      // Initialize permissions data
      this.permissions = [
        { id: 1, name: "Read", type: "User" },
        { id: 2, name: "Write", type: "User" },
        { id: 3, name: "Execute", type: "Admin" },
      ];
    },

    editItem(item) {
      this.editedIndex = this.permissions.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.permissions.indexOf(item);
      this.editedItem = { ...item };
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.permissions.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        this.$set(this.permissions, this.editedIndex, { ...this.editedItem });
      } else {
        this.permissions.push({ ...this.editedItem, id: this.permissions.length + 1 });
      }
      this.close();
    },
  },
};
</script>
