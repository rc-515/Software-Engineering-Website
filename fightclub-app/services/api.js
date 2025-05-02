// services/api.js
import axios from 'axios';

const API_BASE_URL = 'http://172.21.222.30/Software-Engineering-Website/api'; // ← Replace with your local IP

const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

export const getAllUsers = () => api.get('/matches.php?users=true');

// === Auth ===
export const loginUser = (credentials) => api.post('/login.php', credentials);
export const registerUser = (data) => api.post('/signup.php', data);

// === Matches ===
export const getMatches = (email) => api.get(`/matches.php${email ? `?email=${email}` : ''}`);
export const createMatch = (matchData) => api.post('/matches.php', matchData);
export const updateMatch = (id, matchData) => api.put(`/matches.php?id=${id}`, matchData);
export const deleteMatch = (id) => api.delete(`/matches.php?id=${id}`);

// === Swipe ===
export const getPotentialMatches = (email) => {
    return axios.post(`${API_BASE_URL}/matchmaking.php`, { email });
  };
export const swipeMatch = (data) => api.post('/swipe.php', data);

export default api;
