// auth.js
import apiClient from "./axios";
export const register = async (credentials) => {
  const response = await apiClient.post("/admin-register", credentials);
  const { token, user } = response.data;
  if (token) {
    apiClient.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    localStorage.setItem("token", token);
    localStorage.setItem("user", JSON.stringify(user));
  }
  return response.data;
};
export const login = async (credentials) => {
  const response = await apiClient.post("/login", credentials);
  const { token, user } = response.data;
  if (token) {
    apiClient.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    localStorage.setItem("token", token);
    localStorage.setItem("user", JSON.stringify(user));
  }
  return response.data;
};

export const logout = async () => {
  await apiClient.post("/auth/logout");
  delete apiClient.defaults.headers.common["Authorization"];
  localStorage.removeItem("token");
  localStorage.removeItem("user");
};

export const getUser = async () => {
  const token = localStorage.getItem("token");
  if (!token) return null;

  try {
    const response = await apiClient.get("/auth/user", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error("Failed to fetch user", error);
    return null;
  }
};
