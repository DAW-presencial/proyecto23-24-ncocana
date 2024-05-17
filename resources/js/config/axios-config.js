import axios from 'axios';

function getBaseUrl() {
    const { hostname } = window.location;
    if (hostname === 'mybookmarks.randion.es') {
        return 'https://mybookmarks.randion.es/api/v1/';
    } else {
        return 'http://127.0.0.1:8000/api/v1/';
    }
}

const baseURL = getBaseUrl();

axios.defaults.baseURL = baseURL;
axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';
axios.defaults.headers.post['Content-Type'] = 'application/vnd.api+json';


export default axios;
