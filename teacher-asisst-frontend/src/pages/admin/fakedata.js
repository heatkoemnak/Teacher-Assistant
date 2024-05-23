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
    phone: "+855-120-234-123",
    dep_id: 1,
  },
  {
    id: 2,
    teacher_id: "TC002",
    name: "Teacher 2",
    email: "teacher2@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
    phone: "+855-120-234-123",
    dep_id: 2,
  },
  {
    id: 3,
    teacher_id: "TC003",
    name: "Teacher 3",
    email: "teacher3@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
    dep_id: 1,
  },
  {
    id: 4,
    teacher_id: "TC004",
    name: "Teacher 4",
    email: "teacher3@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
    dep_id: 2,
  },
  {
    id: 5,
    teacher_id: "TC005",
    name: "Teacher 5",
    email: "teacher5@example.com",
    subject: "English",
    date_of_birth: "01/01/1980",
    gender: "Male",
    dep_id: 3,
  },
  {
    id: 6,
    teacher_id: "TC006",
    name: "Teacher 6",
    email: "teacher6@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
    dep_id: 3,
  },
  {
    id: 7,
    teacher_id: "TC007",
    name: "Teacher 7",
    email: "teacher7@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
    dep_id: 1,
  },
  {
    id: 8,
    teacher_id: "TC008",
    name: "Teacher 8",
    email: "teacher8@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
    dep_id: 1,
  },
  {
    id: 9,
    teacher_id: "TC009",
    name: "Teacher 9",
    email: "teacher9@example.com",
    subject: "English",
    date_of_birth: "01/01/1980",
    gender: "Male",
    dep_id: 3,
  },
  {
    id: 10,
    teacher_id: "TC0010",
    name: "Teacher 10",
    email: "teacher10@example.com",
    subject: "English",
    date_of_birth: "02/02/1981",
    gender: "Female",
    dep_id: 1,
  },
  {
    id: 11,
    teacher_id: "TC0011",
    name: "Teacher 11",
    email: "teacher11@example.com",
    subject: "English",
    date_of_birth: "02/02/1984",
    gender: "Female",
    dep_id: 3,
  },
  {
    id: 12,
    teacher_id: "TC0012",
    name: "Teacher 12",
    email: "teacher12@example.com",
    subject: "English",
    date_of_birth: "02/02/1982",
    gender: "Female",
    dep_id: 4,
  },

  // Add more teachers as needed
];
const dummyDataDepartment = [
  {
    id: 1,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/globe.png",
    title: "Department of Computer Science",
    category: "Computer Science",
    keyword: "CS",
  },
  {
    id: 2,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/cpu.png",
    title: "Department of Information Technology Engineering",
    category: "Technology",
    keyword: "ITE",
  },
  {
    id: 3,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/rocket.png",
    title: "Department of Chemistry",
    category: "Media",
    keyword: "CHE",
  },
  {
    id: 4,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/bulb.png",
    title: "Department of Mathematics",
    category: "Technology",
    keyword: "MATH",
  },
  {
    id: 5,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/raft.png",
    title: "Department of Higher Education Development and Management",
    category: "Travel",
    keyword: "HED",
  },
  {
    id: 6,
    image: "https://cdn.vuetifyjs.com/docs/images/chips/bulb.png",
    title: "Department of Data Science Engineering",
    category: "Travel",
    keyword: "DSE",
  },
  // {
  //   id: 1,
  //   deptId: 1,
  //   name: "Computer Science",
  // },
  // {
  //   id: 2,
  //   deptId: 2,
  //   name: "Data Analysis",
  // },
  // {
  //   id: 3,
  //   deptId: 3,
  //   name: "SE",
  //   fullname: "Software Engineer",
  // },
  // {
  //   id: 4,
  //   deptId: 4,
  //   name: "ENG",
  //   fullname: "English",

  // },
  // {
  //   id: 5,
  //   deptId: 5,
  //   name: "ITE",
  //   fullname:'Information Technology Engineering'
  // },
  // {
  //   id: 6,
  //   deptId: 6,
  //   name: "DSE",
  //   fullname:'Data Science Engineering'
  // },
  // {
  //   id: 6,
  //   deptId: 6,
  //   name: "DSE",
  //   fullname:'Data Science Engineering'
  // },
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
    dep_id: 1,
  },
  {
    id: 5,
    student_id: "STU002",
    name: "Student 4",
    email: "student2@example.com",
    date_of_birth: "04/04/2001",
    gender: "Female",
    class_id: 1,
    dep_id: 1,
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
// const fakeDataAPI = {
//   get(url) {
//     return new Promise((resolve, reject) => {
//       setTimeout(() => {
//         if (endpoints[url]) {
//           resolve({ data: endpoints[url] });
//         } else {
//           reject(new Error("Endpoint not found"));
//         }
//       }, 1000);
//     });
//   },
// };
const fakeDataAPI = {
  async get(url, { params }) {
    return new Promise((resolve) => {
      setTimeout(() => {
        let items = endpoints[url];
        if (url === "/api/teachers" || url === "/api/students") {
          // Apply sorting
          // if (params.departmentId) {
          //   items = items.filter((item) => item.dep_id === params.departmentId);
          // }
          if (params.sortBy && params.sortBy.length) {
            const sortKey = params.sortBy[0];
            const sortOrder = params.sortDesc[0];
            items.sort((a, b) => {
              const aValue = a[sortKey];
              const bValue = b[sortKey];
              if (aValue < bValue) return sortOrder ? 1 : -1;
              if (aValue > bValue) return sortOrder ? -1 : 1;
              return 0;
            });
          }

          // Apply search
          if (params.search) {
            const search = params.search.toLowerCase();
            items = items.filter((item) => {
              return (
                item.name.toLowerCase().includes(search) ||
                item.email.toLowerCase().includes(search) ||
                (item.teacher_id &&
                  item.teacher_id.toLowerCase().includes(search)) ||
                (item.student_id &&
                  item.student_id.toLowerCase().includes(search))
              );
            });
          }

          // Apply pagination
          const start = (params.page - 1) * params.itemsPerPage;
          const end = start + params.itemsPerPage;
          const paginatedItems = items.slice(start, end);

          resolve({
            data: {
              items: paginatedItems,
              totalItems: items.length,
              end,
              start
            },
          });
        } else {
          resolve({
            data: {
              items,
            },
          });
        }
      }, 1000);
    });
  },
};

export default fakeDataAPI;
