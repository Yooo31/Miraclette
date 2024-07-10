        document.addEventListener('DOMContentLoaded', function () {
            const addButton = document.querySelector('#addProductButton');
            const popupForm = document.querySelector('#popup-form-menu');
            const cancelButton = document.querySelector('#cancel-button');

            addButton.addEventListener('click', function () {
                popupForm.classList.remove('hidden');
            });

            cancelButton.addEventListener('click', function () {
                popupForm.classList.add('hidden');
            });

            document.querySelector('#popup-form-menu form').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('/menu/new', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optionnel : ajouter le produit à la liste sans recharger la page
                        popupForm.classList.add('hidden');
                        location.reload(); // Recharge la page pour voir le nouveau produit
                    } else {
                        alert('Erreur lors de l\'ajout du produit');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'ajout du produit');
                });
            });
        });
