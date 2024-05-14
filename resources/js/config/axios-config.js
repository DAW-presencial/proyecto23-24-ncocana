import axios from 'axios';

const baseURL = "http://127.0.0.1:8000/api/v1";

axios.defaults.baseURL = baseURL;
axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';
axios.defaults.headers.post['Content-Type'] = 'application/vnd.api+json';

export default axios;
