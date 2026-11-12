const api = axios.create({
    headers: {
        "Content-Type": "application/json"
    }
});

api.interceptors.request.use(
    (config) => {
        // Normalize the method name to lowercase (e.g., 'get', 'post', 'delete')
        const method = config.method ? config.method.toLowerCase() : '';

        // Only attach the CSRF token for state-changing operations
        if (['post', 'put', 'patch', 'delete'].includes(method)) {
            const token = sessionStorage.getItem("csrf_token") || window.csrfToken;
            
            if (token) {
                config.headers["X-CSRF-TOKEN"] = token;
            }
        }
        
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);