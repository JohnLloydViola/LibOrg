const laravelApi = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
    headers: {
        "Content-Type": "application/json"
    }
});

laravelApi.interceptors.request.use(
    (config) => {
        // Grab the token saved during login
        const token = sessionStorage.getItem("token")
        
        console.log("Raw token from sessionStorage:", token);
        console.log("Token type:", typeof token);
        
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);