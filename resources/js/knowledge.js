function myFunction(checkbox) {
    const comment = checkbox.parentElement.nextElementSibling;
    if (checkbox.checked) {
        comment.style.display = "block";
    } else {
        comment.style.display = "none";
    }
}
