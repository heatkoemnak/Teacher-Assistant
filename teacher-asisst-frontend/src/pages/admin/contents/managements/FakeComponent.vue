<template>
  <v-container>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="students"
        item-key="id"
        height="420"
        :search="search"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Students</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
          </v-toolbar>
        </template>
        <template v-slot:item.class_id="{ item }">
          <span>{{ item.class_id }}</span>
        </template>
        <template v-slot:item.department_id="{ item }">
          <!-- {{ console.log(item) }} -->
          {{ getDepartmentName(item) }}
          <!-- <span>{{ item.department_id }}</span> -->
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small @click="editTeacher(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteTeacher(item)">mdi-delete</v-icon>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import fakeDataAPI from "../../fakedata"; // Adjust the import path as per your project structure

export default {
  data() {
    return {
      headers: [
        { title: "ID", value: "id" },
        { title: "Name", value: "name" },
        { title: "Email", value: "email" },
        { title: "Date of Birth", value: "date_of_birth" },
        { title: "Gender", value: "gender" },
        { title: "Class", value: "class_id" },
        { title: "Department", value: "department_id" },
        { title: "Action", key: "actions", filterable: false, sortable: false },
      ],
      teachers: [],
      students: [],
      departments: [],
      departmentEachClass: [],
      search: "", // Added search property for filtering
    };
  },
  async mounted() {
    try {
      const teacherResponse = await fakeDataAPI.get("/api/teachers");
      this.teachers = teacherResponse.data;

      const studentResponse = await fakeDataAPI.get("/api/students");
      this.students = studentResponse.data;

      const departmentResponse = await fakeDataAPI.get("/api/departments");
      this.departments = departmentResponse.data;
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  },
  methods: {
    editTeacher(teacher) {
      // Implement edit logic for teacher
      console.log("Edit teacher:", teacher);
    },
    deleteTeacher(teacher) {
      // Implement delete logic for teacher
      console.log("Delete teacher:", teacher);
    },
    editStudent(student) {
      // Implement edit logic for student
      console.log("Edit student:", student);
    },
    deleteStudent(student) {
      // Implement delete logic for student
      console.log("Delete student:", student);
    },
    getDepartmentName(deptitem) {
      const department = this.departments.find(
        (dep) => dep.deptId === deptitem.department_id
      );
      department.filter((d) => d);
      this.departmentEachClass.push(department.department_name);
      console.log(this.departmentEachClass);
      // return department
    },
  },
};
</script>

<style>
/* Add any custom styles here */
</style>
