// Toggle Modal
document.querySelectorAll('[data-modal-toggle]').forEach(button => {
    button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-modal-toggle');
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    });
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target.classList.contains('bg-opacity-75')) {
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    }
});

// Get rental detail
document.getElementById('rental_id').addEventListener('change', function() {
    const rentalId = this.value;
    
    if (rentalId) {
        fetch(`/admin/rental-detail/${rentalId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rental = data.data;
                    document.getElementById('rentalInfo').classList.remove('hidden');
                    document.getElementById('userName').textContent = rental.user_name;
                    document.getElementById('batasKembali').textContent = rental.rental_end;
                    
                    // Tampilkan items
                    let itemsHtml = '';
                    rental.items.forEach(item => {
                        itemsHtml += `<li>${item.equipment_name} - ${item.quantity} x Rp ${item.price.toLocaleString('id-ID')}</li>`;
                    });
                    document.getElementById('itemsListContainer').innerHTML = itemsHtml;
                    
                    // Hitung denda berdasarkan tanggal yang dipilih
                    calculateFine();
                }
            });
    } else {
        document.getElementById('rentalInfo').classList.add('hidden');
        document.getElementById('fineCalculation').classList.add('hidden');
    }
});

// Calculate fine when return date changes
document.getElementById('return_date').addEventListener('change', function() {
    calculateFine();
});

function calculateFine() {
    const rentalSelect = document.getElementById('rental_id');
    const returnDate = document.getElementById('return_date').value;
    
    if (rentalSelect.value && returnDate) {
        const selected = rentalSelect.options[rentalSelect.selectedIndex];
        const rentalEnd = new Date(selected.dataset.rentalEnd);
        const returnDateObj = new Date(returnDate);
        
        // Set waktu ke akhir hari untuk perhitungan yang akurat
        rentalEnd.setHours(23, 59, 59);
        returnDateObj.setHours(0, 0, 0);
        
        const diffTime = returnDateObj - rentalEnd;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 0) {
            const fine = diffDays * 1000;
            document.getElementById('fineCalculation').classList.remove('hidden');
            document.getElementById('lateDays').textContent = diffDays;
            document.getElementById('fineAmount').textContent = fine.toLocaleString('id-ID');
        } else {
            document.getElementById('fineCalculation').classList.add('hidden');
        }
    }
}

// Submit form
document.getElementById('submitReturn').addEventListener('click', function() {
    const form = document.getElementById('returnForm');
    const formData = new FormData(form);
    
    fetch('/admin/return/store', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan');
        console.error('Error:', error);
    });
});

// Delete functionality
let deleteId = null;
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        deleteId = this.dataset.id;
        document.getElementById('deleteModal').classList.remove('hidden');
    });
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteId) {
        fetch(`/admin/returns/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        });
    }
});