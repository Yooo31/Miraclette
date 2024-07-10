document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-role').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const roleContainer = document.getElementById(`role-container-${userId}`);
            const roleText = roleContainer.querySelector('.role-text');
            const roleInput = roleContainer.querySelector('.role-input');
            const editRole = roleContainer.querySelector('.edit-role');
            const saveButton = roleContainer.querySelector('.save-role');

            roleText.classList.add('hidden');
            editRole.classList.add('hidden');
            roleInput.classList.remove('hidden');
            saveButton.classList.remove('hidden');
        });
    });

    document.querySelectorAll('.save-role').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const roleInput = document.getElementById(`role-input-${userId}`);
            const newRole = roleInput.value;

            fetch(`/team/set-role/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ role: newRole })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const roleContainer = document.getElementById(`role-container-${userId}`);
                    const roleText = roleContainer.querySelector('.role-text');
                    const editRole = roleContainer.querySelector('.edit-role');
                    roleText.textContent = newRole;
                    roleText.classList.remove('hidden');
                    roleInput.classList.add('hidden');
                    button.classList.add('hidden');
                    editRole.classList.remove('hidden');
                } else {
                    alert('Error updating role');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating role');
            });
        });
    });
});
