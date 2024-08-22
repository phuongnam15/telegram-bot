import "./bootstrap";
import { fetchClient } from "./utils/fetchClient";
import { formatDate } from "./utils/formatDate";
import { showNotification } from "./utils/showNotification";
import { debounce } from "./utils/debounce";

window.fetchClient = fetchClient;
window.formatDate = formatDate;
window.showNotification = showNotification;
window.debounce = debounce;