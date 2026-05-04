function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Fungsi membuka Modal Detail
function openDetailModal(rental, items) {
    const modal = document.getElementById('detailModal');

    // Isi data user & basic info
    document.getElementById('detail_user_name').innerText = rental.user ? rental.user.name : 'Unknown';
    document.getElementById('detail_start_date').innerText = rental.rental_start;
    document.getElementById('detail_end_date').innerText = rental.rental_end;
    document.getElementById('detail_total_price').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(rental.total_price);

    // Isi List Barang
    const itemListDiv = document.getElementById('detail_items_list');
    itemListDiv.innerHTML = ''; // Reset
    items.forEach(item => {
        const div = document.createElement('div');
        div.innerText = '- ' + (item.equipment ? item.equipment.name : 'Unknown Item');
        itemListDiv.appendChild(div);
    });

    // Set Badge Status
    const statusBadge = document.getElementById('detail_status_badge');
    let statusText = '';
    let statusClass = '';

    switch (rental.status) {
        case 'pending':
        case 'menunggu':
            statusText = 'Menunggu';
            statusClass = 'bg-orange-100 text-orange-600';
            break;
        case 'active':
        case 'dipinjam':
            statusText = 'Dipinjam';
            statusClass = 'bg-blue-100 text-blue-600';
            break;
        case 'completed':
        case 'selesai':
            statusText = 'Selesai';
            statusClass = 'bg-green-100 text-green-600';
            break;
        case 'canceled':
        case 'ditolak':
            statusText = 'Ditolak';
            statusClass = 'bg-red-100 text-red-600';
            break;
        default:
            statusText = rental.status;
            statusClass = 'bg-gray-100 text-gray-600';
    }

    statusBadge.innerText = statusText;
    statusBadge.className = `px-3 py-1 rounded-full text-xs font-semibold ${statusClass}`;

    // ===== BAGIAN PEMBAYARAN YANG DIPERBAIKI =====
    // Status Pembayaran
    const paymentStatus = document.getElementById('detail_payment_status');
    const paymentMethod = document.getElementById('detail_payment_method');
    const paymentDate = document.getElementById('detail_payment_date');
    const paymentAmount = document.getElementById('detail_payment_amount');
    const paymentProof = document.getElementById('detail_payment_proof');

    // Cek apakah ada data payment (pake singular karena relasi di model pake 'payment')
    if (rental.payment && rental.payment.length > 0) {
        const payment = rental.payment[0]; // Ambil data payment pertama
        
        // Status pembayaran dengan badge
        paymentStatus.innerHTML = '';
        const statusSpan = document.createElement('span');
        statusSpan.className = payment.payment_status === 'lunas' || payment.payment_status === 'paid' 
            ? "bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold"
            : "bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold";
        statusSpan.innerText = payment.payment_status;
        paymentStatus.appendChild(statusSpan);
        
        // Method pembayaran
        paymentMethod.innerText = payment.payment_method || '-';
        
        // Tanggal pembayaran
        paymentDate.innerText = payment.payment_date || '-';
        
        // Jumlah dibayar
        if (payment.amount) {
            paymentAmount.innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(payment.amount);
        } else {
            paymentAmount.innerText = '-';
        }

        if(payment.payment_proof) {
            paymentProof.innerHTML = `
                <a href="${payment.payment_proof}" target="_blank" 
                   class="text-blue-600 hover:text-blue-800 underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Lihat Bukti Transfer
                </a>
                <br>
                <span class="text-xs text-gray-500">${payment.payment_proof}</span>
            `;
        } else {
            paymentProof.innerHTML = '<span class="text-gray-400">Tidak ada bukti transfer</span>';
        }
    } else {
        // Kalau tidak ada data payment
        paymentStatus.innerHTML = '';
        const statusSpan = document.createElement('span');
        statusSpan.className = "bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold";
        statusSpan.innerText = "Belum Bayar";
        paymentStatus.appendChild(statusSpan);
        
        paymentMethod.innerText = '-';
        paymentDate.innerText = '-';
        paymentAmount.innerText = '-';
        paymentProof.innerHTML = '<span class="text-gray-400">Tidak ada bukti transfer</span>';
    }

    // Tampilkan modal
    modal.classList.remove('hidden');
}

// Fungsi membuka Modal Edit Status
// function openEditStatusModal(id, currentStatus) {
//     const modal = document.getElementById('editStatusModal');

//     // Update Action URL Form
//     let url = "{{ route('admin.rentals.updateStatus', ':id') }}";
//     url = url.replace(':id', id);
//     document.getElementById('editStatusForm').action = url;

//     // Set Selected Option pada Dropdown
//     document.getElementById('status_select').value = currentStatus;

//     modal.classList.remove('hidden');
// }
