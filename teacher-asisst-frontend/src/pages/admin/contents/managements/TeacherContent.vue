
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
        <v-toolbar-title>Manage Teacher</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn class="mb-2" color="primary" dark v-bind="props">
              Add New Teacher
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
                  <v-col cols="12" md="12" sm="6">
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
    <template v-slot:item.account="{ item }">
      <v-chip
        append-icon="mdi-open-in-new"
        color="orange"
        to="/admin/profile/account"
      >
        <span>Account</span>
      </v-chip>
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
      Loading... 
    </template>
  </v-data-table>
</template>
<script>
import fakeDataAPI from "../../fakedata";

export default {
  data: () => ({
    dialog: false,
    dialogDelete: false,
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
    select: ["Read", "Update"],
    items: ["Create", "Read", "Update", "Delete", "Vuetify"],
    desserts: [],
    types: ["Admin", "User"],
    editedIndex: -1,
    teachers: [],
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
    this.GetDataApi();
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
        {
          id: 7,
          name: "Frozen Yogurt",
          type: "User",
        },
        {
          id: 8,
          name: "Ice cream sandwich",
          type: "User",
        },
        {
          id: 9,
          name: "Eclair",
          type: "Admin",
        },
        {
          id: 10,
          name: "Cupcake",
          type: "User",
        },
        {
          id: 11,
          name: "Gingerbread",
          type: "Admin",
        },
        {
          id: 12,
          name: "Jelly bean",
          type: "User",
        },
      ];
    },
     async GetDataApi() {
      await fakeDataAPI.get("/api/teachers")
        .then((response) => {
          this.desserts = response.data;
        })
        .catch((error) => {
          alert(error);
          this.loading = false;
        });
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      console.log(this.editedItem.name);
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





<!-- <template>
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
    <template v-slot:item.actions="{ item }">
      <div class="d-flex justify-start align-center">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </div>
    </template>
    <template v-slot:top>
      <div class="d-flex align-center">
        <v-text-field
          v-model="name"
          class="ma-2"
          density="compact"
          placeholder="Search name..."
          hide-details
          width="20"
          variant="outlined"
          prepend-inner-icon="mdi-magnify"
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn
          color="white"
          class="bg-primary ml-2 white--text"
          @click="addNew"
        >
          <v-icon dark>mdi-plus</v-icon>Add
        </v-btn>
      </div>
    </template>
  </v-data-table-server>
</template>
<script>
import fakeDataAPI from "../../fakedata";
function generateData() {
  const data = [
    {
      id: 1,
      name: "Frozen Yogurt",
      teacher_id: 12,
      email: "teacher1@gmail.com",
      subject: "Subject 2",
      date_of_birth: "10/05/1993",
      gender: "Male",
      edit: "Edit",
      delete: "Delete",
    },
    {
      id: 2,
      name: "Jelly bean",
      teacher_id: 24,
      email: "teacher2@gmail.com",
      subject: "Subject 0",
      date_of_birth: "15/07/1991",
      gender: "Female",
      edit: "Edit",
      delete: "Delete",
    },
    // Add more objects as needed
  ];

  return data;
}

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = fakeDataAPI.teachers
          .slice()
          .filter((item) => {
            if (
              search.name &&
              !item.name.toLowerCase().includes(search.name.toLowerCase())
            ) {
              return false;
            }

            if (
              search.teacher_id &&
              !(item.teacher_id >= Number(search.teacher_id))
            ) {
              return false;
            }

            return true;
          });

        if (sortBy.length) {
          const sortKey = sortBy[0].key;
          const sortOrder = sortBy[0].order;
          items.sort((a, b) => {
            const aValue = a[sortKey];
            const bValue = b[sortKey];
            return sortOrder === "desc" ? bValue - aValue : aValue - bValue;
          });
        }

        const paginated = items.slice(start, end);

        resolve({ items: paginated, total: items.length });
      }, 500);
    });
  },
};

export default {
  data: () => ({
    itemsPerPage: 6,
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
    generatedData: [],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    student_id: 0,
    search: "",
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
    student_id() {
      this.search = String(Date.now());
    },
  },

  mounted() {
    console.log(fakeDataAPI);
  },

  methods: {
    // async GetDataApi() {
    //   await fakeDataAPI.get("/api/teachers")
    //     .then((response) => {
    //       this.serverItems = response.data.data;
    //       this.totalItems = parseInt(response.data.count);
    //       this.loading = false;
    //     })
    //     .catch((error) => {
    //       alert(error);
    //       this.loading = false;
    //     });
    // },

    loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      FakeAPI.fetch({
        page,
        itemsPerPage,
        sortBy,
        search: { name: this.name, student_id: this.student_id },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
  },
};
</script> -->
