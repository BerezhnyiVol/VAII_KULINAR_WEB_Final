document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".button-delete").forEach(button => {
        button.addEventListener("click", function () {
            const recipeId = this.dataset.id; // Získanie ID receptu

            if (!recipeId) {
                alert("Chyba: ID receptu nie je definované.");
                return;
            }

            if (!confirm("Naozaj chcete vymazať tento recept?")) {
                return;
            }

            fetch(`/VAII_KULINAR_WEB/public/index.php/recipe/delete/${recipeId}`, {
                method: "DELETE",
                headers: { "Content-Type": "application/json" },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 1️⃣ Odstránenie prvku z DOM
                        const recipeElement = document.getElementById(`recipe-${recipeId}`);
                        if (recipeElement) {
                            recipeElement.remove();
                        }

                        // 2️⃣ Zobrazenie vyskakovacej správy
                        showSuccessMessage("✅ Recept bol úspešne vymazaný!");

                    } else {
                        alert("❌ Chyba: " + (data.error || "Nepodarilo sa vymazať recept."));
                    }
                })
                .catch(error => {
                    console.error("Chyba pri vymazaní:", error);
                    alert("❌ Chyba: server vrátil nesprávne údaje.");
                });
        });
    });
});

// 🔹 Funkcia na zobrazenie správy o úspešnom vymazaní
function showSuccessMessage(message) {
    const messageBox = document.getElementById("success-message");
    messageBox.textContent = message;
    messageBox.style.display = "block";

    setTimeout(() => {
        messageBox.style.display = "none";
    }, 3000); // Správa zmizne po 3 sekundách
}
