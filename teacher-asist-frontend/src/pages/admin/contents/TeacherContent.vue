<template>
  <v-data-table-server
    :items-per-page="itemsPerPage"
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
  >
    <template v-slot:item.actions="{ item }">
      <div class="d-flex justify-start align-center">
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </div>
    </template>
    <template v-slot:top>
      <v-toolbar flat color="white">
        <div class="d-flex align-center w-100">
          <v-text-field
            v-model="name"
            append-icon="mdi-magnify"
            label="Search Name"
            dense
            class="mt-2"
            hide-details
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
      </v-toolbar>
    </template>
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
    "Honeycomb",
    "Donut",
  ];

  const subjects = ["Math", "English", "Physics"];

  for (let i = 1; i <= 12; i++) {
    // const randomGenderIndex = Math.floor(Math.random() * genders.length);
    const teacher_id = i * 12;
    const name = desserts[i];
    const email = `teacher${i}@gmail.com`;
    const subject = `Subject ${Math.floor(Math.random() * subjects.length)}`;
    const date_of_birth = `${Math.floor(Math.random() * 28) + 1}/0${
      Math.floor(Math.random() * 9) + 1
    }/199${Math.floor(Math.random() * 10)}`;
    const gender = genders[i];
    const edit = "Edit";
    const del = "Delete";

    data.push({
      id: i,
      name,
      teacher_id,
      email,
      subject,
      date_of_birth,
      gender,
      edit,
      delete: del,
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
    console.log(this.student_id);
  },

  methods: {
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
