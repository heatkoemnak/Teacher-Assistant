// Sample student data
let teachers = []; // Changed to let to allow reassignment
let students = []; // Changed to let to allow reassignment

const dummyDataTeacher = [];
const dummyDataStudent = [];
const minYear = 1950;
const maxYear = new Date().getFullYear() - 18;
const year = Math.floor(Math.random() * (maxYear - minYear + 1)) + minYear;
const month = Math.floor(Math.random() * 12) + 1;
const maxDay = new Date(year, month, 0).getDate();
const day = Math.floor(Math.random() * maxDay) + 1;
const formattedMonth = month < 10 ? `0${month}` : month;
const formattedDay = day < 10 ? `0${day}` : day;
const birthdate = `${formattedMonth}/${formattedDay}/${year}`;
const generateDummyTeachers = () => {
  for (let i = 1; i <= 8; i++) {
    const teacher = {
      id: i,
      teacher_id: `TC00${i}`,
      name: `Teacher ${i}`,
      email: `teacher${i}@example.com`,
      date_of_birth: birthdate,
      gender: Math.floor(Math.random() * 2) === 0 ? "Male" : "Female",
      edit: "Edit",
      delete: "Delete",
    };
    dummyDataTeacher.push(teacher);
  }
  return dummyDataTeacher;
};
teachers = generateDummyTeachers();

const generateDummyStudents = () => {
  for (let i = 1; i <=6; i++) {
    const student = {
      id: i,
      student_id: `TC00${i}`,
      name: `Student ${i}`,
      email: `student${i}@example.com`,
      date_of_birth: birthdate,
      gender: Math.floor(Math.random() * 2) === 0 ? "Male" : "Female",
      edit: "Edit",
      delete: "Delete",
    };
    dummyDataStudent.push(student);
  }
  return dummyDataStudent;
};

students = generateDummyStudents(); // Assign the generated dummy data to teachers

// Define endpoints
const endpoints = {
  "/api/students": students,
  "/api/teachers": teachers,
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
