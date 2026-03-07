import axios from 'vue-store/src/plugins/axios.js'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

// Interceptor de request: adjunta el token si existe
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Interceptor de response: manejo global de errores
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // if (error.response?.status === 401) {
    //   localStorage.removeItem('token')
    //   window.location.href = '/login'
    // }
    return Promise.reject(error)
  }
)

export default api

