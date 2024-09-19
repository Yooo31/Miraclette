const modalEl = document.getElementById('info-popup');
const privacyModal = new Modal(modalEl, {
    placement: 'center'
});

const closeModalEl = document.getElementById('close-modal');
closeModalEl.addEventListener('click', function() {
    privacyModal.hide();
});

const acceptPrivacyEl = document.getElementById('confirm-button');
acceptPrivacyEl.addEventListener('click', function() {
    alert('privacy accepted');
    privacyModal.hide();
});

document.querySelectorAll('.orderCard').forEach(card => {
  card.addEventListener('click', function() {
    privacyModal.show();
    const orderId = this.getAttribute('data-order-id');

    fetch(`/order/details/${orderId}`)
      .then(response => response.json())
      .then(data => {

        const popup = document.getElementById('info-popup');
        const title = popup.querySelector('h3');
        const list = popup.querySelector('ul');

        title.textContent = `Commande n°${data.id}`;
        list.innerHTML = data.menus.map((menu, index) =>
          `<li id="menu-item-${index}" class="menu-item">
          ${menu.name} - Quantité: ${menu.count}
          </li>`
        ).join('');

        document.querySelectorAll('.menu-item').forEach(item => {
          item.addEventListener('click', () => {
            item.classList.toggle('line-through');
          });
        });

        const statusSelect = document.getElementById('status-select');
        statusSelect.value = data.status;

        statusSelect.addEventListener('change', function() {
          const newStatus = this.value;

          fetch(`/order/update-status/${orderId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.reload();
            } else {
              alert('Erreur lors de la mise à jour du statut:', data.error);
            }
          })
          .catch(error => console.error('Error updating status:', error));
        });
      })
      .catch(error => console.error('Erreur:', error));
  });
});


document.getElementById('close-popup').addEventListener('click', function() {
  document.getElementById('order-popup').classList.add('hidden');
});
