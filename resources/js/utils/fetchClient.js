import { showNotification } from "./showNotification";

export async function fetchClient(url, options = {}) {
    const token = localStorage.getItem("access_token");

    options.headers = {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
    };

    try {
        const response = await fetch(window.location.origin + url, options);

        if (response.status === 401) {
            window.location.href = "/login";
            return;
        }

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message);
        }

        return response.json();
    } catch (error) {
        showNotification(error.message, "error");
        throw error;
    }
}
