// Sample student data
let teachers = [];
let students = [];
let department = [];

const dummyDataTeacher = [
  {
    id: 1,
    teacher_id: "TC001",
    name: "Teacher 1",
    email: "teacher1@example.com",
    date_of_birth: "01/01/1980",
    gender: "Male",
    class_id: 1,
    department_id: 2,
  },
  {
    id: 2,
    teacher_id: "TC002",
    name: "Teacher 2",
    email: "teacher2@example.com",
    date_of_birth: "02/02/1981",
    gender: "Female",
    class_id: 1,
    department_id: 1,
  },
  {
    id: 3,
    teacher_id: "TC003",
    name: "Teacher 3",
    email: "teacher3@example.com",
    date_of_birth: "02/02/1984",
    gender: "Female",
    class_id: 4,
    department_id: 1,
  },
  {
    id: 4,
    teacher_id: "TC004",
    name: "Teacher 4",
    email: "teacher3@example.com",
    date_of_birth: "02/02/1982",
    gender: "Female",
    class_id: 1,
    department_id: 1,
  },
  // Add more teachers as needed
];
const dummyDataDepartment = [
  {
    id: 1,
    deptId: 1,
    department_name: "Computer Science",
  },
  {
    id: 2,
    deptId: 2,
    department_name: "Data Analysis",
  },
  {
    id: 3,
    deptId: 3,
    department_name: "Software Engineer",
  },
  {
    id: 4,
    deptId: 4,
    department_name: "English",
  },
  {
    id: 5,
    deptId: 5,
    department_name: "ITE",
  },
];

const dummyDataStudent = [
  {
    id: 1,
    student_id: "STU001",
    name: "Student 1",
    email: "student1@example.com",
    date_of_birth: "03/03/2000",
    gender: "Male",
    class_id: 1,
    department_id: 1,
  },
  {
    id: 2,
    student_id: "STU002",
    name: "Student 2",
    email: "student2@example.com",
    date_of_birth: "04/04/2001",
    gender: "Female",
    class_id: 1,
    department_id: 1,
  },
  {
    id: 3,
    student_id: "STU003",
    name: "Student 3",
    email: "student2@example.com",
    date_of_birth: "04/04/2001",
    gender: "Female",
    class_id: 2,
    department_id: 3,
  },
  {
    id: 4,
    student_id: "STU004",
    name: "Student 4",
    email: "studen4@example.com",
    date_of_birth: "04/04/2001",
    gender: "Female",
    class_id: 1,
    department_id: 3,
  },
  {
    id: 5,
    student_id: "STU002",
    name: "Student 4",
    email: "student2@example.com",
    date_of_birth: "04/04/2001",
    gender: "Female",
    class_id: 1,
    department_id: 2,
  },
  // Add more students as needed
];

teachers = dummyDataTeacher;
students = dummyDataStudent;
department = dummyDataDepartment;

// Define endpoints
const endpoints = {
  "/api/students": students,
  "/api/teachers": teachers,
  "/api/departments": department,
};

// Fake Data API
const fakeDataAPI = {
  get(url) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        if (endpoints[url]) {
          resolve({ data: endpoints[url] });
        } else {
          reject(new Error("Endpoint not found"));
        }
      }, 1000);
    });
  },
};

export default fakeDataAPI;
