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
      })
      .catch(error => console.error('Erreur:', error));
  });
});

document.getElementById('close-popup').addEventListener('click', function() {
  document.getElementById('order-popup').classList.add('hidden');
});
