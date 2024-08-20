export function showNotification(message, type) {
    const toast = document.getElementById("toast-element");
    const toastMessage = document.getElementById("toast-message");
    const toastIcon = document.getElementById("toast-icon");

    toastMessage.innerHTML = message;

    // Clear previous icon
    toastIcon.innerHTML = "";

    if (type === "success") {
        toastIcon.innerHTML = `
            <svg class="shrink-0 size-4 text-teal-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
            </svg>`;
    } else if (type === "error") {
        toastIcon.innerHTML = `
            <svg class="shrink-0 size-4 text-red-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
            </svg>`;
    } else if (type === "warning") {
        toastIcon.innerHTML = `
            <svg class="shrink-0 size-4 text-yellow-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
            </svg>`;
    }

    // Show the toast
    toast.classList.remove("-right-full");
    toast.classList.add("right-3");

    // Set up the close button event listener
    const closeButton = toast.querySelector('[aria-label="Close"]');
    closeButton.onclick = () => {
        hideToast();
    };

    // Hide the notification after 3 seconds
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    const toast = document.getElementById("toast-element");
    toast.classList.remove("right-3");
    toast.classList.add("-right-full");
}
