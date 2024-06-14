<template>
  <v-data-table-server
    v-model:items-per-page="itemsPerPage"
    :headers="headers"
    :items="serverItems"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
    @update:options="loadItems"
    disable-pagination
    disable-sort
    hide-default-footer
  >
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
            class="text-none font-weight-regular"
            color="primary"
            prepend-icon="mdi-account"
            text="Generate all students"
            variant="tonal"
          ></v-btn>
      </div>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-btn @click="generateAllStudents" color="primary">Generate</v-btn>
      <v-icon size="small" @click="deleteItem(item)"> mdi-delete </v-icon>
    </template>
  </v-data-table-server>
</template>
<script>
const students = [
  {
    id: 1,
    name: "Mony",
    student_id: "S001",
    gender: "Female",
    attendance: 92,
    assignment: 85,
    quiz: 80,
    midterm: 88,
    final: 90,
    overall: 86,
    attendanceDates: {},
  },
  {
    id: 2,
    name: "Jnint",
    student_id: "S002",
    gender: "Male",
    attendance: 85,
    assignment: 80,
    quiz: 75,
    midterm: 83,
    final: 87,
    overall: 81,
    attendanceDates: {},
  },
  {
    id: 3,
    name: "Nak",
    student_id: "S003",
    gender: "Female",
    attendance: 78,
    assignment: 70,
    quiz: 68,
    midterm: 72,
    final: 74,
    overall: 71,
    attendanceDates: {},
  },
  {
    id: 4,
    name: "Dara",
    student_id: "S004",
    gender: "Male",
    attendance: 88,
    assignment: 85,
    quiz: 82,
    midterm: 86,
    final: 89,
    overall: 85,
    attendanceDates: {},
  },
  {
    id: 5,
    name: "Bona",
    student_id: "S005",
    gender: "Female",
    attendance: 95,
    assignment: 92,
    quiz: 91,
    midterm: 94,
    final: 96,
    overall: 93,
    attendanceDates: {},
  },
  {
    id: 6,
    name: "Morn",
    student_id: "S006",
    gender: "Male",
    attendance: 90,
    assignment: 88,
    quiz: 85,
    midterm: 87,
    final: 90,
    overall: 87,
    attendanceDates: {},
  },
  {
    id: 7,
    name: "Lollipop",
    student_id: "S007",
    gender: "Female",
    attendance: 82,
    assignment: 79,
    quiz: 75,
    midterm: 80,
    final: 83,
    overall: 80,
    attendanceDates: {},
  },
  {
    id: 8,
    name: "Cupcake",
    student_id: "S008",
    gender: "Male",
    attendance: 91,
    assignment: 89,
    quiz: 87,
    midterm: 90,
    final: 92,
    overall: 90,
    attendanceDates: {},
  },
  {
    id: 9,
    name: "Honeycomb",
    student_id: "S009",
    gender: "Female",
    attendance: 89,
    assignment: 85,
    quiz: 82,
    midterm: 87,
    final: 90,
    overall: 86,
    attendanceDates: {},
  },
  {
    id: 10,
    name: "Donut",
    student_id: "S010",
    gender: "Male",
    attendance: 87,
    assignment: 83,
    quiz: 80,
    midterm: 85,
    final: 88,
    overall: 84,
    attendanceDates: {},
  },
];

const FakeAPI = {
  async fetch({ page, itemsPerPage, sortBy, search }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const items = students.slice().filter((item) => {
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
  async deleteStudent(studentId) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        const index = students.findIndex((student) => student.id === studentId);
        if (index !== -1) {
          students.splice(index, 1);
          resolve();
        } else {
          reject(new Error("Student not found"));
        }
      }, 500);
    });
  },
};

export default {
  data: () => ({
    itemsPerPage: 5,
    headers: [
      { title: "Name", key: "name" },
      { title: "Gender", key: "gender" },
      { title: "Assignment", key: "assignment" },
      { title: "Attendance", key: "attendance" },
      { title: "Quiz", key: "quiz" },
      { title: "Midterm", key: "midterm" },
      { title: "Final", key: "final" },
      { title: "Overall", key: "overall" },
      { title: "Actions", key: "actions", sortable: false },
    ],
    serverItems: [],
    loading: true,
    totalItems: 0,
    name: "",
    search: "",
    dynamicHeaders: [],
    currentStudent: null,
  }),

  created() {
    this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: [] });
  },
  methods: {
    loadItems({ page, itemsPerPage, sortBy }) {
      this.loading = true;
      FakeAPI.fetch({
        page,
        itemsPerPage,
        sortBy,
        search: { name: this.name },
      }).then(({ items, total }) => {
        this.serverItems = items;
        this.totalItems = total;
        this.loading = false;
      });
    },
    saveStudent(updatedStudent) {
      const index = this.serverItems.findIndex(
        (student) => student.id === updatedStudent.id
      );
      if (index !== -1) {
        this.serverItems.splice(index, 1, updatedStudent);
        console.log("Updated student:", updatedStudent);
      } else {
        console.error("Student not found in serverItems:", updatedStudent);
      }
    },
    generateAllStudents() {
      console.log("Generating all students...");
    },
    deleteItem(item) {
      if (!confirm(`Are you sure you want to delete ${item.name}?`)) {
        return;
      }

      FakeAPI.deleteStudent(item.id)
        .then(() => {
          const index = this.serverItems.findIndex((student) => student.id === item.id);
          if (index !== -1) {
            this.serverItems.splice(index, 1);
            console.log("Deleted item:", item);
          } else {
            console.error("Item not found in serverItems:", item);
          }
        })
        .catch((error) => {
          console.error("Error deleting student:", error.message);
        });
    },
  },
};
</script>
