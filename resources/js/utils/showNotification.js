export function showNotification(message, type) {
    const notification = document.getElementById("notification");
    const notificationText = notification.querySelector('p');

    // Update the message text
    notificationText.textContent = message;

    // Remove any existing background color classes
    notification.classList.remove('bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500');

    // Add the appropriate background color class based on the type
    if (type === 'success') {
        notification.classList.add('bg-green-500');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500');
    } else if (type === 'info') {
        notification.classList.add('bg-blue-500');
    } else if (type === 'warning') {
        notification.classList.add('bg-yellow-500');
    }

    // Show the notification
    notification.classList.remove('hidden');
    notification.classList.add('block');

    // Hide the notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('block');
        notification.classList.add('hidden');
    }, 3000);
}