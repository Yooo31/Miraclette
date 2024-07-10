document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.querySelector('#addBookingButton');
    const popupForm = document.querySelector('#popup-form-booking');
    const cancelButton = document.querySelector('#cancel-button');

    addButton.addEventListener('click', function () {
        popupForm.classList.remove('hidden');
    });

    cancelButton.addEventListener('click', function () {
        popupForm.classList.add('hidden');
    });

    document.querySelector('#popup-form-booking form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/booking/new', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                popupForm.classList.add('hidden');
                location.reload();
            } else {
                alert('Erreur lors de l\'ajout de la réservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'ajout de la réservation');
        });
    });
});
