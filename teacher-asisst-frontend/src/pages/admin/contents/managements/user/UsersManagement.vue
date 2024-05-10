<template>
  <v-data-table
    :headers="headers"
    :items="desserts"
    :sort-by="[{ key: 'calories', order: 'asc' }]"
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
              Add New Role
            </v-btn>
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
                    <v-combobox
                      v-model="editedItem.type"
                      :items="types"
                      label="Type"
                    ></v-combobox>
                  </v-col>
                  <v-col cols="12"  md="12" sm="6">
                    <v-combobox
                      v-model="select"
                      :items="items"
                      label="User Permissions"
                      multiple
                    >
                      <template v-slot:selection="data">
                        <v-chip
                          :key="JSON.stringify(data.item)"
                          v-bind="data.attrs"
                          :disabled="data.disabled"
                          :model-value="data.selected"
                          size="small"
                          @click:close="data.parent.selectItem(data.item)"
                        >
                          <template v-slot:prepend>
                            <v-avatar class="bg-accent text-uppercase" start>{{
                              data.item.title.slice(0, 1)
                            }}</v-avatar>
                          </template>
                          {{ data.item.title }}
                        </v-chip>
                      </template>
                    </v-combobox>
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
    <template v-slot:item.permissions="{ item }">
      <v-chip
        append-icon="mdi-open-in-new"
        color="orange"
        @click="editItem(item)"
      >
        <v-icon class="me-2" size="small" @click="editItem(item)">
          mdi-pencil
        </v-icon>
        <span>Edit</span>
      </v-chip>
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
      {
        title: "No.",
        align: "start",
        sortable: false,
        key: "id",
      },
      { title: "User Name", key: "name" },
      { title: "User Type", key: "type" },
      { title: "Permissions", key: "permissions" },
      { title: "Actions", key: "actions", sortable: false },
    ],
    select: ["Read", "Update"],
    items: ["Create","Read", "Update", "Delete", "Vuetify"],
    desserts: [],
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
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Role" : "Edit Role";
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
      this.desserts = [
        {
          id: 1,
          name: "Frozen Yogurt",
          type: "User",
        },
        {
          id: 2,
          name: "Ice cream sandwich",
          type: "User",
        },
        {
          id: 3,
          name: "Eclair",
          type: "Admin",
        },
        {
          id: 4,
          name: "Cupcake",
          type: "User",
        },
        {
          id: 5,
          name: "Gingerbread",
          type: "Admin",
        },
        {
          id: 6,
          name: "Jelly bean",
          type: "User",
        },
      ];
    },

    editItem(item) {
      console.log(item);
      // this.dessert += 1;
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
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
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
      } else {
        this.editedItem.id = this.desserts.length + 1;
        console.log(this.dessert);
        this.desserts.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>
