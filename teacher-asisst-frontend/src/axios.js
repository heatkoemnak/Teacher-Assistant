import axios from "axios";

const instance = axios.create({
  baseURL: "http://localhost:8000/api", // Your Laravel API URL
  withCredentials: true,
});

export default instance;
