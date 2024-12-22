function showDeleteAlert(userId) {
  Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
  }).then((result) => {
      if (result.isConfirmed) {
          deleteUser(userId);
      }
  });
}

function deleteUser(userId) {
  fetch('user_operations.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=delete&userId=${userId}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          Swal.fire("Deleted!", data.message, "success");
          document.querySelector(`tr[data-user-id="${userId}"]`).remove();
          updateTotalUsers(-1);
      } else {
          Swal.fire("Error!", data.message, "error");
      }
  })
  .catch(error => {
      console.error('Error:', error);
      Swal.fire("Error!", "An unexpected error occurred", "error");
  });
}

function showEditAlert(userId, username, email, profilePicUrl) {
  Swal.fire({
      title: 'Edit User',
      html:
          `<img src="${profilePicUrl}" alt="Profile Picture" style="width:100px;height:100px;border-radius:50%;margin-bottom:15px;"><br>` +
          `<input id="swal-input-username" class="swal2-input" value="${username}" placeholder="Username">` +
          `<input id="swal-input-email" class="swal2-input" value="${email}" placeholder="Email">`,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Update',
      cancelButtonText: 'Cancel',
      preConfirm: () => {
          return {
              username: document.getElementById('swal-input-username').value,
              email: document.getElementById('swal-input-email').value
          }
      }
  }).then((result) => {
      if (result.isConfirmed) {
          updateUser(userId, result.value.username, result.value.email);
      }
  });
}

function updateUser(userId, username, email) {
  fetch('user_operations.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=edit&userId=${userId}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          Swal.fire("Updated!", data.message, "success");
          updateUserRow(userId, username, email);
      } else {
          Swal.fire("Error!", data.message, "error");
      }
  })
  .catch(error => {
      console.error('Error:', error);
      Swal.fire("Error!", "An unexpected error occurred", "error");
  });
}

function updateUserRow(userId, username, email) {
  const row = document.querySelector(`tr[data-user-id="${userId}"]`);
  if (row) {
      row.querySelector('.text-heading').textContent = username;
      row.querySelector('td:nth-child(2)').textContent = email;
  }
}

function updateTotalUsers(change) {
  const totalUsersElement = document.querySelector('.card-body .h3.font-bold');
  if (totalUsersElement) {
      let currentTotal = parseInt(totalUsersElement.textContent);
      totalUsersElement.textContent = currentTotal + change;
  }
}