
$('#statusFilter, #floorFilter').on('change', function(){
    loadBeds();
});
function loadBeds(transaksi=null) {
    const status = $('#statusFilter').val();
    const floor = $('#floorFilter').val();

    $.ajax({
        url: window.location.origin+'/data/status-beds',
        data: {
            status: status,
            floor: floor,
            transaksi: transaksi
        },
        beforeSend :function(){
            $('.modal-load').show();
        },
        success: function (data) {
            renderBeds(data);
            $('.modal-load').hide();
        }
    });
}


function renderBeds(data) {
    const container = $('#floorsContainer');
    container.html('');

    // Jika data kosong sama sekali
    if (Object.keys(data).length === 0) {
        container.html(`
            <div class="alert alert-light-warning text-center">
                <i class="bi bi-exclamation-triangle-fill"></i> Tidak ada bed yang tersedia untuk filter saat ini.
            </div>
        `);
        return;
    }

    // Per lantai
    Object.keys(data).forEach(lantai => {
        let floorHtml = `<div class="floor-section">
            <div class="h5 text-center">Lantai ${lantai}</div>`;

        const rooms = data[lantai];
        Object.values(rooms).forEach(room => {
            floorHtml += `
            <div class="room-section">
                <div class="room-header">${room.room_name}</div>`;

            if (room.beds.length === 0) {
                floorHtml += `
                    <div class="alert alert-light small text-muted text-center">
                        Tidak ada bed dalam ruangan ini.
                    </div>`;
            } else {
                floorHtml += `<div class="compact-grid">`;

                room.beds.forEach(bed => {
                    const statusClass = {
                        'available': 'bed-available',
                        'occupied': 'bed-occupied',
                        'maintenance': 'bed-maintenance'
                    }[bed.status] || 'bed-unknown';

                    // Badge dokter & karyawan hanya saat status = occupied
                    let dokterHtml = '';
                    let karyawanHtml = '';

                    if (bed.status === 'occupied') {
                        dokterHtml = bed.dokters?.map(d =>
                            `<span class="badge bg-light-secondary">${d.dokter_nama}</span>`
                        ).join('') || '-';

                        karyawanHtml = bed.karyawans?.map(k =>
                            `<span class="badge bg-light-info">${k.karyawan_nama}</span>`
                        ).join('') || '-';
                    }

                    floorHtml += `
                    <div class="bed-card ${statusClass}" data-id="${bed.id}" 
                        data-bed="${bed.bed_number}" 
                        data-room="${room.room_name}" 
                        data-floor="${lantai}">
                        <div class="bed-number">${bed.bed_number}</div>
                        <div class="bed-info">
                            <div class="pasien-name"><strong>Status:</strong> ${bed.status}</div>`;

                    if (bed.status === 'occupied') {
                        floorHtml += `
                            <div class="dokters"><strong>Dokter:</strong><br>${dokterHtml}</div>
                            <div class="karyawans"><strong>Karyawan:</strong><br>${karyawanHtml}</div>`;
                    }

                    floorHtml += `
                        </div>
                    </div>`;
                });

                floorHtml += `</div>`; // end compact-grid
            }

            floorHtml += `</div>`; // end room-section
        });

        floorHtml += `</div>`; // end floor-section
        container.append(floorHtml);
    });
}


