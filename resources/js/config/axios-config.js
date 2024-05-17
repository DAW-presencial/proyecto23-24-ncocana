import axios from 'axios';

// Base Url
function getBaseUrl() {
    return window.location.hostname === 'mybookmarks.randion.es' ? 'https://mybookmarks.randion.es/api/v1' : 'http://127.0.0.1:8000/api/v1';
}

// Headers
axios.defaults.baseURL = getBaseUrl();
axios.defaults.headers.common['Authorization'] = localStorage.getItem('token');
axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';
axios.defaults.headers.post['Content-Type'] = 'application/vnd.api+json';

export default axios;
