document.addEventListener("DOMContentLoaded", () => {
    console.log("dialog.js chargé ✅");

    document.querySelectorAll(".open-dialog-btn").forEach(button => {
        button.addEventListener("click", () => {
            const dialogId = button.getAttribute("data-dialog-id");
            const dialog = document.getElementById(dialogId);
            if (dialog) dialog.showModal();
        });
    });

    document.querySelectorAll(".close-dialog-btn").forEach(button => {
        button.addEventListener("click", () => {
            const dialogId = button.getAttribute("data-dialog-id");
            const dialog = document.getElementById(dialogId);
            if (dialog) dialog.close();
        });
    });
});
