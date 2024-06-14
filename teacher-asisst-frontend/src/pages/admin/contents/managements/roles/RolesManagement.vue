<template>
  <v-container>
    <v-card>
      <v-card-title>Roles</v-card-title>
      <v-data-table
        :headers="subjectHeaders"
        :items="filteredRoles"
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
              >New Role</v-btn
            >
          </v-toolbar>
        </template>
        <template v-slot:item.permissions="{ item }">
          <div class="d-flex flex-row flex-wrap">
            <v-chip
              v-for="permission in item.permissions"
              :key="permission.id"
              :style="{ backgroundColor: randomColor() }"
            >
              {{ permission.name }}
            </v-chip>
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <div class="d-flex align-center justify-end">
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
    </v-card>

    <!-- Create Dialog -->
    <v-dialog v-model="dialogCreate" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">New Role</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="createForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Role Name"
              v-model="newRole.name"
              :rules="[required]"
              required
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeCreateDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="addRole">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit Dialog -->
    <v-dialog v-model="dialogEdit" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Edit Role</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="editForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Role Name"
              v-model="roleToEdit.name"
              :rules="[required]"
              required
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeEditDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="editRoleConfirm"
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
        <v-card-text>Are you sure you want to delete this role?</v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeDeleteDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="red" text @click="deleteRoleConfirm">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      roles: [],
      subjectHeaders: [
        { title: "Role Name", value: "name" },
        { title: "ID", value: "id" },
        { title: "Permissions", value: "permissions", sortable: false },
        { title: "Actions", value: "actions", sortable: false, align: "end" },
      ],
      loading: true,
      search: "",
      dialogCreate: false,
      dialogEdit: false,
      dialogDelete: false,
      valid: false,
      newRole: {
        name: "",
      },
      roleToEdit: null,
      required: (value) => !!value || "Required.",
    };
  },
  computed: {
    filteredRoles() {
      return this.roles.filter((role) =>
        role.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  methods: {
    fetchRoles() {
      this.loading = true;
      axios
        .get("/roles")
        .then((response) => {
          this.roles = response.data;
          return axios.get("/permissions");
        })
        .then((response) => {
          const permissions = response.data;
          this.roles = this.roles.map(role => ({
            ...role,
            permissions: permissions.filter(permission => permission.role_id === role.id),
          }));
        })
        .catch((error) => {
          console.error("Error fetching roles or permissions:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    randomColor() {
      const letters = '0123456789ABCDEF';
      let color = '#';
      for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    },
    openCreateDialog() {
      this.dialogCreate = true;
    },
    closeCreateDialog() {
      this.dialogCreate = false;
      this.resetForm(this.$refs.createForm);
    },
    addRole() {
      if (this.$refs.createForm.validate()) {
        axios
          .post("/roles", this.newRole)
          .then(() => {
            this.fetchRoles();
            this.newRole.name = "";
            this.dialogCreate = false;
          })
          .catch((error) => {
            console.error("Error adding role:", error);
          });
      }
    },
    openEditDialog(item) {
      this.roleToEdit = { ...item };
      this.dialogEdit = true;
    },
    closeEditDialog() {
      this.dialogEdit = false;
      this.resetForm(this.$refs.editForm);
    },
    editRoleConfirm() {
      if (this.$refs.editForm.validate()) {
        axios
          .put(`/roles/${this.roleToEdit.id}`, this.roleToEdit)
          .then(() => {
            this.fetchRoles();
            this.dialogEdit = false;
          })
          .catch((error) => {
            console.error("Error editing role:", error);
          });
      }
    },
    openDeleteDialog(item) {
      this.roleToEdit = item;
      this.dialogDelete = true;
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
      this.roleToEdit = null;
    },
    deleteRoleConfirm() {
      axios
        .delete(`/roles/${this.roleToEdit.id}`)
        .then(() => {
          this.fetchRoles();
          this.dialogDelete = false;
        })
        .catch((error) => {
          console.error("Error deleting role:", error);
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
    this.fetchRoles();
  },
};
</script>

<style scoped>
.v-data-table-header {
  background-color: #f5f5f5;
}
</style>
