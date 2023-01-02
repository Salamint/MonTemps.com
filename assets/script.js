function copier(id) {
    link = "https://localhost/MonTemps.com/browse.php?" + id;
    navigator.clipboard.writeText(link);
}