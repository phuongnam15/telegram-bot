import "./bootstrap";
import { fetchClient } from "./utils/fetchClient";
import { formatDate } from "./utils/formatDate";
import { showNotification } from "./utils/showNotification";

window.fetchClient = fetchClient;
window.formatDate = formatDate;
window.showNotification = showNotification;