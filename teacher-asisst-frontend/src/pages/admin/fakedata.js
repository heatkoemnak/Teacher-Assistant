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
    subject: "English",
    date_of_birth: "01/01/1980",
    gender: "Male",
  },
  {
    id: 2,
    teacher_id: "TC002",
    name: "Teacher 2",
    email: "teacher2@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
  },
  {
    id: 3,
    teacher_id: "TC003",
    name: "Teacher 3",
    email: "teacher3@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
  },
  {
    id: 4,
    teacher_id: "TC004",
    name: "Teacher 4",
    email: "teacher3@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
  },
  {
    id: 5,
    teacher_id: "TC005",
    name: "Teacher 5",
    email: "teacher5@example.com",
    subject: "English",
    date_of_birth: "01/01/1980",
    gender: "Male",
  },
  {
    id: 6,
    teacher_id: "TC006",
    name: "Teacher 6",
    email: "teacher6@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
  },
  {
    id: 7,
    teacher_id: "TC007",
    name: "Teacher 7",
    email: "teacher7@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
  },
  {
    id: 8,
    teacher_id: "TC008",
    name: "Teacher 8",
    email: "teacher8@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
  },
  {
    id: 9,
    teacher_id: "TC009",
    name: "Teacher 9",
    email: "teacher9@example.com",
    subject: "English",
    date_of_birth: "01/01/1980",
    gender: "Male",
  },
  {
    id: 10,
    teacher_id: "TC0010",
    name: "Teacher 10",
    email: "teacher10@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
  },
  {
    id: 11,
    teacher_id: "TC0011",
    name: "Teacher 11",
    email: "teacher11@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
  },
  {
    id: 12,
    teacher_id: "TC0012",
    name: "Teacher 12",
    email: "teacher12@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
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
// const endpoints = {
//   students,
//   teachers,
//   department,
// };

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
