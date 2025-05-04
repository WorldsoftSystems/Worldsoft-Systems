// --- formatDate.js ---

export function formatDate(dateString) {
    if (!dateString || typeof dateString !== "string") {
        console.error("Invalid dateString:", dateString);
        return "Invalid Date";
    }

    var parts = dateString.split('-');
    if (parts.length !== 3) {
        console.error("Unexpected date format:", dateString);
        return "Invalid Date";
    }

    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}
