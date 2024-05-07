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
          class="ma-2"
          density="compact"
          placeholder="Search name..."
          hide-details
          width="20"
          variant="outlined"
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

    <!-- <template #item="{ item }">
      <tr>
        <td>{{ item.id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.student_id }}</td>
        <td>{{ item.email }}</td>
        <td>{{ item.classes }}</td>
        <td>{{ item.date_of_birth }}</td>
        <td>{{ item.gender }}</td>
        <td class="justify-center layout px-0">
          <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
        </td>
      </tr>
    </template> -->
  </v-data-table-server>
</template>
<script>
function generateData() {
  const data = [];
  const genders = [
    "",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
    "Male",
    "Female",
  ];
  const desserts = [
    "",
    "Frozen Yogurt",
    "Jelly bean",
    "KitKat",
    "Eclair",
    "Gingerbread",
    "Ice cream sandwich",
    "Lollipop",
    "Cupcake",
    "Honeycomb",
    "Donut",
    "Combo",
    "Donn",
  ];

  for (let i = 1; i <= 12; i++) {
    // const randomGenderIndex = Math.floor(Math.random() * genders.length);
    const student_id = `STU00${i}`;
    const name = desserts[i];
    const email = `student${i}@gmail.com`;
    const classes = `M ${i}`;
    const date_of_birth = `${Math.floor(Math.random() * 28) + 1}/0${
      Math.floor(Math.random() * 9) + 1
    }/199${Math.floor(Math.random() * 10)}`;
    const gender = genders[i];

    data.push({
      id: i,
      name,
      student_id,
      email,
      classes,
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
      {
        title: "Email",
        key: "email",
      },
      {
        title: "Class",
        key: "classes",
      },
      { title: "Date Of Birth", key: "date_of_birth" },
      { title: "Gender", key: "gender" },
      { title: "Action", key: "actions", filterable: false, sortable: false },
    ],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    student_id: "",
    search: "",

    editedIndex: -1,
    editedItem: {
      id: 0,
      name: "",
      calories: 0,
    },
  }),
  watch: {
    name() {
      this.search = String(Date.now());
    },
    student_id() {
      this.search = String(Date.now());
    },
  },

  methods: {
    deleteItem(item) {
      console.log(item);
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
        search: { name: this.name, student_id: this.student_id },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
  },
};
</script>
