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
    <template v-slot:item.male="{ item }">
      <v-chip :color="getStatusColor(item.male)">{{ item.male }}</v-chip>
    </template>
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
  const total_teacher = [
    0, 20, 20, 30, 40, 50, 60, 70, 80, 90, 100, 10, 20, 30, 40, 50, 60, 70, 80,
    90, 100,
  ];
  const total_student = [
    92, 15, 10, 25, 30, 40, 50, 60, 20, 80, 90, 100, 20, 25, 30, 43, 21, 30, 62,
    72,
  ];
  const gender_of_students = [
    20, 22, 30, 40, 50, 60, 70, 80, 90, 100, 10, 20, 30, 40, 50, 60, 70, 80, 90,
    100,
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
    const class_id = `CLS00${i}`;
    const class_name = desserts[i];
    const total_students = total_student[i];
    const total_teachers = total_teacher[i];
    const male = `${gender_of_students[i]}%`;

    data.push({
      id: i,
      class_name,
      class_id,
      total_students,
      total_teachers,
      male,
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
      { title: "Class Name", key: "class_name" },
      { title: "Class.ID", key: "class_id" },
      { title: "Total Students", key: "total_students" },
      {
        title: "Total Teachers",
        key: "total_teachers",
      },
      {
        title: "Male",
        key: "male",
      },
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
    getStatusColor(male) {
      if (male == `${100}%`) return "green";
      else if ((male >= `${50}%`) & (male < `${95}%`)) return "orange";
      else return "red";
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
