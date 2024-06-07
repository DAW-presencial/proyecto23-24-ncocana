import axios from 'axios';

let $baseUrl;

if (window.location.hostname === 'mybookmarks.randion.es') {
    $baseUrl = 'https://mybookmarks.randion.es';
} else if (window.location.hostname === 'mybookmarks.local') {
    $baseUrl = 'http://mybookmarks.local';
} else if (window.location.hostname === 'mybookmarks-dev.randion.es') {
    $baseUrl = 'https://mybookmarks-dev.randion.es';
} else {
    $baseUrl = 'http://127.0.0.1:8000';
}

// Base Url
function getBaseUrl() {
    return $baseUrl;
}

// Headers
axios.defaults.baseURL = getBaseUrl();
axios.defaults.headers.common['Authorization'] = localStorage.getItem('token');
axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';
axios.defaults.headers.common['Content-Type'] = 'application/vnd.api+json';

// Interceptor para manejar errores en producción
if (process.env.NODE_ENV === 'production') {
    axios.interceptors.response.use(
        response => response,
        error => {
            // Silenciar los errores en producción
            return Promise.reject(error);
        }
    );
}

export default axios;
