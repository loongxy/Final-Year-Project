let body = document.body
let profile = document.querySelector('.header .flex .profile');
let searchForm = document.querySelector('.header .flex .search-form');
let sideBar = document.querySelector('.side-bar');

document.querySelector('#user-btn').onclick = () =>{
        profile.classList.toggle('active');
        searchForm.classList.remove('active');
}

document.querySelector('#menu-btn').onclick = () =>{
        sideBar.classList.toggle('active');
        body.classList.toggle('active');
}

document.querySelector('#close-bar').onclick = () =>{
        sideBar.classList.remove('active');
}

window.onscroll = () =>{
        profile.classList.remove('active');
        searchForm.classList.remove('active');

        if(window.innerWidth < 1200){
                sideBar.classList.remove('active');
                body.classList.remove('active');
        }
}

// update apointment
function updateStatus(selectElement) {
    const appointmentId = selectElement.getAttribute('data-id');
    const newStatus = selectElement.value;

    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: appointmentId, status: newStatus }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully!');
            } else {
                alert('Failed to update status.');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
}

// update quantity
$(document).ready(function() {
        $('.quantity-btn').on('click', function() {
            const id = $(this).data('id');
            const action = $(this).data('action');
            const quantityElement = $(this).siblings('.quantity-value');
    
            $.ajax({
                url: 'update_quantity.php',
                type: 'POST',
                data: { id: id, action: action },
                timeout: 5000,
                success: function(response) {
                    if (response.success) {
                        quantityElement.text(response.newQuantity);
                    } else {
                        alert(response.message || 'Error updating quantity!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred while updating the quantity.');
                }
            });
        });
    });