document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.button-delete').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // ❌ Остановка стандартного перехода

            const recipeId = this.dataset.recipeId;
            const recipeCard = document.getElementById(`recipe-${recipeId}`);

            if (!confirm('Naozaj chcete vymazať tento recept?')) return; // ✅ Только одно подтверждение

            fetch(`/VAII_KULINAR_WEB/public/index.php/recipe/delete/${recipeId}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Stránka neexistuje.');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        recipeCard.remove(); // ✅ Удаление из DOM
                        console.log(`Recept ${recipeId} bol úspešne vymazaný!`);
                    } else {
                        alert('❌ Chyba pri mazaní receptu: ' + data.message);
                    }
                })
                .catch(error => console.error('❌ Chyba pri odosielaní požiadavky:', error));
        });
    });
});
