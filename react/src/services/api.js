import axios from 'axios';

const api = axios.create({
    baseURL: 'https://dedstudio.com.br/bejobs/ws/'
});

export default api;