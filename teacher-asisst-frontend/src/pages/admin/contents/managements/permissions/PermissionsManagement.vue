<template>
  <v-container>
    <v-card>
      <v-card-title>Permissions</v-card-title>
      <v-data-table
        :headers="headers"
        :items="filteredPermissions"
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
              >New Permission</v-btn
            >
          </v-toolbar>
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
          <span class="headline">New Permission</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="createForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Permission Name"
              v-model="newPermission.name"
              :rules="[required]"
              required
            ></v-text-field>
            <v-select
              label="Role"
              v-model="newPermission.role_id"
              :items="roleOptions"
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
          <v-btn color="blue darken-1" text @click="addPermission">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit Dialog -->
    <v-dialog v-model="dialogEdit" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Edit Permission</span>
        </v-card-title>
        <v-card-text>
          <v-form ref="editForm" v-model="valid" lazy-validation>
            <v-text-field
              label="Permission Name"
              v-model="permissionToEdit.name"
              :rules="[required]"
              required
            ></v-text-field>
            <v-select
              label="Role"
              v-model="permissionToEdit.role_id"
              :items="roleOptions"
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
          <v-btn color="blue darken-1" text @click="editPermissionConfirm"
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
        <v-card-text
          >Are you sure you want to delete this permission?</v-card-text
        >
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="closeDeleteDialog"
            >Cancel</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="red" text @click="deletePermissionConfirm"
            >Delete</v-btn
          >
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
      permissions: [],
      headers: [
        { title: "Permission Name", value: "name" },
        { title: "ID", value: "id" },
        { title: "Role ID", value: "role_id" },
        { title: "Actions", value: "actions", sortable: false, align: "end" },
      ],
      loading: true,
      search: "",
      dialogCreate: false,
      dialogEdit: false,
      dialogDelete: false,
      valid: false,
      roleOptions: [],
      newPermission: {
        name: "",
        role_id: "",
      },
      permissionToEdit: null,
      required: (value) => !!value || "Required.",
    };
  },
  computed: {
    filteredPermissions() {
      return this.permissions.filter((permission) =>
        permission.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  methods: {
    fetchPermissions() {
      this.loading = true;
      axios
        .get("/permissions")
        .then((response) => {
          this.permissions = response.data;
        })
        .catch((error) => {
          console.error("Error fetching permissions:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchRoles() {
      this.loading = true;
      axios
        .get("/roles")
        .then((response) => {
          this.roles = response.data;
          this.roleOptions = [
            { title: "All", value: "" },
            ...this.roles.map((role) => ({ title: role.name, value: role.id })),
          ];
        })
        .catch((error) => {
          console.error("Error fetching roles:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    openCreateDialog() {
      this.dialogCreate = true;
    },
    closeCreateDialog() {
      this.dialogCreate = false;
      this.resetForm(this.$refs.createForm);
    },
    addPermission() {
      if (this.$refs.createForm.validate()) {
        axios
          .post("/permissions", this.newPermission)
          .then(() => {
            this.fetchPermissions();
            this.newPermission.name = "";
            this.newPermission.role_id = "";
            this.dialogCreate = false;
          })
          .catch((error) => {
            console.error("Error adding permission:", error);
          });
      }
    },
    openEditDialog(item) {
      this.permissionToEdit = { ...item };
      this.dialogEdit = true;
    },
    closeEditDialog() {
      this.dialogEdit = false;
    },
    editPermissionConfirm() {
      if (this.$refs.editForm.validate()) {
        axios
          .put(
            `/permissions/${this.permissionToEdit.id}`,
            this.permissionToEdit
          )
          .then(() => {
            this.fetchPermissions();
            this.permissionToEdit.name = "";
            this.permissionToEdit.role_id = "";
            this.dialogEdit = false;
          })
          .catch((error) => {
            console.error("Error editing permission:", error);
          });
      }
    },
    openDeleteDialog(item) {
      this.permissionToEdit = item;
      this.dialogDelete = true;
    },
    closeDeleteDialog() {
      this.dialogDelete = false;
    },
    deletePermissionConfirm() {
      axios
        .delete(`/permissions/${this.permissionToEdit.id}`)
        .then(() => {
          this.fetchPermissions();
          this.dialogDelete = false;
        })
        .catch((error) => {
          console.error("Error deleting permission:", error);
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
    this.fetchPermissions();
    this.fetchRoles();
  },
};
</script>

<style scoped>
.v-data-table-header {
  background-color: #f5f5f5;
}
</style>
