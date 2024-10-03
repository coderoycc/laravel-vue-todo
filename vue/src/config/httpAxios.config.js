import store from "@store/index";
import axios from "axios";
import { URL_API } from "./constants.js";
import router from '../router';
const tokenheader = localStorage.getItem('app_tk') ?? '';
const instance = axios.create({
  baseURL: URL_API,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
    "Authorization": `Bearer ${tokenheader}` 
  },
});

/**
 * Interceptor para cerrar session cuando el token haya vencido.
 */
instance.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === 401) {
      store.commit('clearTokenSession');
      router.push('/login');
    }
    return Promise.reject(error);
  }
);

export default instance;
