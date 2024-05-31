<template>
  <div>
    <div class="table-top">
      <div class="search-wrapper">
        <input
          type="text"
          v-model="search"
          placeholder="Search for a student by name or email..."
          class="search-input"
        />
        <button class="search-button" @click="searchStudents">Search</button>
      </div>
      <button class="add-button" @click="addNewStudent">Add Student</button>
    </div>
    <table class="student-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Gender</th>
          <th>Assignment</th>
          <th>Attendance</th>
          <th>Quiz</th>
          <th>Midterm</th>
          <th>Final</th>
          <th>Overall</th>
          <th>GPA</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="student in filteredStudents" :key="student.id">
          <td>{{ student.name }}</td>
          <td>{{ student.gender }}</td>
          <td>{{ getScore(student, "Assignment") }}</td>
          <td>{{ getScore(student, "Attendance") }}</td>
          <td>{{ getScore(student, "Quiz") }}</td>
          <td>{{ getScore(student, "Midterm") }}</td>
          <td>{{ getScore(student, "Final") }}</td>
          <td>{{ student.overall }}</td>
          <td>{{ student.gpa }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import studentsData from "@/assets/student.json";

export default {
  data() {
    return {
      search: "",
      students: studentsData.studentsData,
    };
  },
  computed: {
    filteredStudents() {
      return this.students.filter(
        (student) =>
          student.name.toLowerCase().includes(this.search.toLowerCase()) ||
          student.email.toLowerCase().includes(this.search.toLowerCase())
      );
    },
  },
  methods: {
    searchStudents() {
      // This method is not strictly necessary since the filtering is handled by the computed property
    },
    getScore(student, subject) {
      const score = student.scores.find((s) => s.subject === subject);
      return score ? score.score : "";
    },
    addNewStudent() {
      console.log("Add new student");
    },
  },
};
</script>

<style scoped>
.table-top {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  align-items: center;
}

.search-wrapper {
  display: flex;
  align-items: center;
}

.search-input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.search-button,
.add-button {
  margin-left: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.search-button {
  background-color: #007bff;
  color: white;
}

.search-button:hover {
  background-color: #0056b3;
}

.add-button {
  background-color: #28a745;
  color: white;
}

.add-button:hover {
  background-color: #218838;
}

.student-table {
  width: 100%;
  border-collapse: collapse;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.student-table th,
.student-table td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}

.student-table th {
  background-color: #f8f9fa;
  color: #333;
  font-weight: bold;
}

.student-table tr:nth-child(even) {
  background-color: #f2f2f2;
}

.student-table tr:hover {
  background-color: #e9ecef;
}

.student-table td {
  font-size: 14px;
  color: #333;
}
</style>
