console.log('hey2');

document.addEventListener('DOMContentLoaded', function () {
  const increaseButtons = document.querySelectorAll('.increase-button');
  const decreaseButtons = document.querySelectorAll('.decrease-button');

  const validateButton = document.getElementById('sendRequest');
  const idTableInput = document.getElementById('idTableInput');
  const errorMessage = document.getElementById('error-message');

  increaseButtons.forEach(button => {
      button.addEventListener('click', function () {
          const id = button.getAttribute('data-id');
          let quantityElement = document.getElementById(`quantity-${id}`);
          let currentQuantity = parseInt(quantityElement.innerText);
          quantityElement.innerText = currentQuantity + 1;
      });
  });

  decreaseButtons.forEach(button => {
      button.addEventListener('click', function () {
          const id = button.getAttribute('data-id');
          let quantityElement = document.getElementById(`quantity-${id}`);
          let currentQuantity = parseInt(quantityElement.innerText);
          if (currentQuantity > 0) {
              quantityElement.innerText = currentQuantity - 1;
          }
      });
  });

  validateButton.addEventListener('click', function () {
      if (idTableInput.value === '') {
        errorMessage.classList.remove('hidden');
        idTableInput.classList.add('border-2', 'border-red-500');
          return;
      }
      const orderLines = [{ 'clientId': idTableInput.value }];

      document.querySelectorAll('.order-line').forEach(orderLine => {
          const id = orderLine.getAttribute('data-id');
          const quantity = parseInt(document.getElementById(`quantity-${id}`).innerText);
          if (quantity > 0) {
              orderLines.push({ id, quantity });
          }
      });

      console.log(JSON.stringify(orderLines));

      fetch('/order/new', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderLines),
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
            window.location.href = '/order/success';
          } else {
              alert('Erreur lors de la validation de la commande');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Erreur lors de la validation de la commande');
      });
  });
});
