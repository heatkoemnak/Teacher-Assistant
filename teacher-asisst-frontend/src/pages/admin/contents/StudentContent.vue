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
    <template #item.actions="{ item }">
      <div class="d-flex justify-start align-center">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </div>
    </template>
    <template v-slot:top>
      <div class="d-flex align-center">
        <v-text-field
          v-model="name"
          class="ma-2 hidden-sm-and-down"
          density="compact"
          placeholder="Search name..."
          hide-details
          width="20"
          variant="outlined"
          prepend-inner-icon="mdi-magnify"
        ></v-text-field>
        <v-select
          v-model="selectedDepartment"
          :items="departments"
          class="mt-5"
          density="compact"
          item-title="All "
          label="Find by Department"
        ></v-select>
        <v-select
          v-model="selectedClass"
          :items="classes"
          class="mt-5"
          density="compact"
          label="Find by Class"
        ></v-select>
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
function generateData() {
  const data = [];
  const genders = ["", "Male", "Female"];
  const departments = [
    "",
    "Department C",
    "Department A",
    "Department B",
    "Department C",
    "Department A",
    "Department B",
    "Department C",
    "Department A",
    "Department B",
    "Department C",
    "Department A",
    "Department B",
  ];
  const classes = [
    "",
    "Class 1",
    "Class 2",
    "Class 3",
    "Class 1",
    "Class 2",
    "Class 3",
    "Class 1",
    "Class 2",
    "Class 3",
    "Class 1",
    "Class 2",
    "Class 3",
  ];

  for (let i = 1; i <= 12; i++) {
    const student_id = `STU00${i}`;
    const name = `Student ${i}`;
    const email = `student${i}@gmail.com`;
    const department =
      departments[Math.floor(Math.random() * departments.length)];
    const _class = classes[Math.floor(Math.random() * classes.length)];
    const date_of_birth = `${Math.floor(Math.random() * 28) + 1}/0${
      Math.floor(Math.random() * 9) + 1
    }/199${Math.floor(Math.random() * 10)}`;
    const gender = genders[Math.floor(Math.random() * departments.length)];

    data.push({
      id: i,
      name,
      student_id,
      email,
      department,
      class: _class,
      date_of_birth,
      gender,
    });
  }
  return data;
}

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = generateData()
          .slice()
          .filter((item) => {
            if (
              search.name &&
              !item.name.toLowerCase().includes(search.name.toLowerCase())
            ) {
              return false;
            }
            if (search.department && item.department !== search.department) {
              return false;
            }
            if (search.class && item.class !== search.class) {
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
      { title: "Student.ID", key: "student_id" },
      { title: "Email", key: "email" },
      { title: "Department", key: "department" },
      { title: "Class", key: "class" },
      { title: "Date Of Birth", key: "date_of_birth" },
      { title: "Gender", key: "gender" },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    departments: [
      "All Departments",
      "Department A",
      "Department B",
      "Department C",
    ],
    classes: ["All Classes", "Class 1", "Class 2", "Class 3"],
    selectedDepartment: "",
    selectedClass: "",
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    search: "",
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
    selectedDepartment() {
      this.search = String(Date.now());
    },
    selectedClass() {
      this.search = String(Date.now());
    },
  },
  methods: {
    deleteItem(item) {
      const index = this.serverItems.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.serverItems.splice(index, 1);
    },
    loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      FakeAPI.fetch({
        page,
        itemsPerPage,
        sortBy,
        search: {
          name: this.name,
          department: this.selectedDepartment,
          class: this.selectedClass,
        },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
  },
};
</script>
