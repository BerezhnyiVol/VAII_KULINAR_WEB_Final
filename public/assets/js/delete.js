document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".button-delete").forEach(button => {
        button.addEventListener("click", function () {
            const recipeId = this.dataset.id; // Получаем ID рецепта

            if (!recipeId) {
                alert("Ошибка: ID рецепта не определён.");
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
                        // 1️⃣ Удаляем элемент из DOM
                        const recipeElement = document.getElementById(`recipe-${recipeId}`);
                        if (recipeElement) {
                            recipeElement.remove();
                        }

                        // 2️⃣ Показываем всплывающее сообщение
                        showSuccessMessage("✅ Рецепт успешно удалён!");

                    } else {
                        alert("❌ Ошибка: " + (data.error || "Не удалось удалить рецепт."));
                    }
                })
                .catch(error => {
                    console.error("Ошибка при удалении:", error);
                    alert("❌ Ошибка: сервер вернул некорректные данные.");
                });
        });
    });
});

// 🔹 Функция для отображения сообщения об успешном удалении
function showSuccessMessage(message) {
    const messageBox = document.getElementById("success-message");
    messageBox.textContent = message;
    messageBox.style.display = "block";

    setTimeout(() => {
        messageBox.style.display = "none";
    }, 3000); // Сообщение исчезает через 3 секунды
}
