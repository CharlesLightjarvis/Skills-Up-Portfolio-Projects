import axios from "axios";

export const api = axios.create({
  baseURL: "https://api.escuelajs.co/api/v1",
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});
