window.initFotoTab = function () {
    if ($('#tab-foto').length === 0) return;

    // Cegah inisialisasi ulang
    if ($('#tab-foto').data('initialized')) return;
    $('#tab-foto').data('initialized', true);

    const uploadedPhotos = {};
    const uploadUrl = $('#tab-foto').data('upload');
    const deleteUrl = $('#tab-foto').data('delete');
    const csrfToken = $('meta[name="csrf-token"]').attr('content') || 'demo-token';
    let isUploading = false;

    const existingPhotos = $('#tab-foto').data('list');
    const pendaftaranId = $('input[name="x"]').val();
    
    existingPhotos.forEach(photo => {
        const position = photo.position.toUpperCase();
        const path = photo.foto;
        const label = getPositionLabel(position);
        const $slot = $(`#photoSlot_${position}`);
        const imageUrl = `/images/foto_before/${pendaftaranId}/${path}`;

        $slot.html(`
            <img src="${imageUrl}" class="photo-preview" alt="${label}">
            <div class="photo-overlay">Klik untuk ganti foto</div>
            <button type="button" class="photo-remove-btn" data-position="${position}">×</button>
            <input type="file" id="photoInput_${position}" name="foto_${position}" 
                accept="image/*" style="display: none;" data-position="${position}">
        `).attr('data-position', position).addClass('has-image');

        uploadedPhotos[position] = {
            file: null,
            url: imageUrl,
            response: { path: path, url: imageUrl }
        };
    });

    updatePhotoCounter();

    $(document).off('click.foto').on('click.foto', '.photo-slot', function (e) {
        if ($(e.target).is('input') || $(e.target).is('button') || $(e.target).closest('button').length) return;
        e.stopPropagation();
        const position = $(this).data('position');
        $(`#photoInput_${position}`).trigger('click');
    });

    $(document).off('change.foto').on('change.foto', 'input[type="file"]', function (e) {
        const file = this.files[0];
        const position = $(this).data('position');
        if (file && position) uploadPhoto(position, file);
        $(this).val('');
    });

    $(document).off('click.removefoto').on('click.removefoto', '.photo-remove-btn', function (e) {
        e.preventDefault(); e.stopPropagation();
        const position = $(this).data('position');
        if (position) removePhoto(position);
    });

    $(document).off('click.clearall').on('click.clearall', '#clearAllPhotos', function (e) {
        e.preventDefault();
        clearAllPhotos();
    });

    $(document).off('dragover.foto dragenter.foto').on('dragover.foto dragenter.foto', '.photo-slot', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    $(document).off('drop.foto').on('drop.foto', '.photo-slot', function (e) {
        e.preventDefault(); e.stopPropagation();
        const files = e.originalEvent.dataTransfer.files;
        const position = $(this).data('position');
        if (files.length > 0 && position) uploadPhoto(position, files[0]);
    });

    function uploadPhoto(position, file) {
        if (!file.type.startsWith('image/')) return showError(position, 'File harus berupa gambar');
        if (file.size > 5 * 1024 * 1024) return showError(position, 'Ukuran file maksimal 5MB');

        const $slot = $(`#photoSlot_${position}`);
        $slot.addClass('uploading');
        isUploading = true;
        $slot.append('<div class="loading-spinner"></div>');
        const $progress = $('<div class="progress-bar-upload"></div>');
        $slot.append($progress);

        compressImage(file).then(compressedFile => {
            const formData = new FormData();
            formData.append('foto', compressedFile);
            formData.append('position', position);
            formData.append('pendaftaran_id', $('input[name="pendaftaran_id"]').val());

            $.ajax({
                url: uploadUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                xhr: function () {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            $progress.css('width', percent + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    handleUploadSuccess(position, compressedFile, response);
                },
                error: function (xhr) {
                    handleUploadError(position, xhr);
                },
                complete: function () {
                    $slot.removeClass('uploading');
                    $slot.find('.loading-spinner').remove();
                    $slot.find('.progress-bar-upload').remove();
                    isUploading = false;
                }
            });
        }).catch(err => {
            showError(position, 'Gagal mengkompres gambar');
            $slot.removeClass('uploading');
            $slot.find('.loading-spinner').remove();
            $slot.find('.progress-bar-upload').remove();
            isUploading = false;
        });
    }


    function handleUploadSuccess(position, file, response) {
        const $slot = $(`#photoSlot_${position}`);
        const reader = new FileReader();
        reader.onload = function (e) {
            const label = getPositionLabel(position);
            $slot.html(`
                <img src="${e.target.result}" class="photo-preview" alt="${label}">
                <div class="photo-overlay">Klik untuk ganti foto</div>
                <button type="button" class="photo-remove-btn" data-position="${position}">×</button>
                <input type="file" id="photoInput_${position}" name="foto_${position}" 
                    accept="image/*" style="display: none;" data-position="${position}">
            `).attr('data-position', position).addClass('has-image');

            uploadedPhotos[position] = {
                file: file,
                url: e.target.result,
                response: response
            };

            updatePhotoCounter();
            clearError(position);
        };
        reader.readAsDataURL(file);
    }

    function handleUploadError(position, xhr) {
        let msg = 'Upload gagal.';
        if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
        else if (xhr.status === 422 && xhr.responseJSON?.errors)
            msg = Object.values(xhr.responseJSON.errors).flat().join(', ');
        else if (xhr.status === 413) msg = 'File terlalu besar';
        else if (xhr.status === 0) msg = 'Koneksi terputus';
        showError(position, msg);
    }

    function removePhoto(position) {
        if (isUploading) {
            sweetalert2ToastTE('warning','Warning!','Tunggu upload selesai terlebih dahulu!');
            return;
        }

        const $slot = $(`#photoSlot_${position}`);
        const label = getPositionLabel(position);
        $.ajax({
            url: deleteUrl,
            method: 'POST',
            data: {
                position: position,
                pendaftaran_id: $('input[name="pendaftaran_id"]').val(),
                _token: csrfToken
            },
            success: function () {
                $slot.removeClass('has-image').html(`
                    <div class="upload-placeholder">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">${label}</div>
                        <div class="upload-subtext">Klik untuk upload</div>
                    </div>
                    <input type="file" id="photoInput_${position}" name="foto_${position}" 
                        accept="image/*" style="display: none;" data-position="${position}">
                `);
                delete uploadedPhotos[position];
                updatePhotoCounter();
                clearError(position);
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Gagal menghapus foto';
                sweetalert2ToastTE('error','Error!', msg);
            }
        });
    }

    function clearAllPhotos() {
        if (isUploading) return alert('Tunggu upload selesai terlebih dahulu');
        const positions = Object.keys(uploadedPhotos);
        if (positions.length === 0) return alert('Tidak ada foto untuk dihapus');
        if (confirm(`Yakin ingin menghapus ${positions.length} foto?`)) {
            positions.forEach(pos => removePhoto(pos));
        }
    }

    function updatePhotoCounter() {
        $('#photoCount').text(Object.keys(uploadedPhotos).length);
    }

    function getPositionLabel(position) {
        const labels = {
            'I': 'Foto Depan',
            'II': 'Serong Kanan',
            'III': 'Kanan',
            'IV': 'Serong Kiri',
            'V': 'Kiri'
        };
        return labels[position] || position.toUpperCase();
    }

    function showError(position, message) {
        const $slot = $(`#photoSlot_${position}`);
        $slot.find('.error-message').remove();
        $slot.append(`<div class="error-message">${message}</div>`);
        setTimeout(() => clearError(position), 5000);
    }

    function clearError(position) {
        $(`#photoSlot_${position}`).find('.error-message').remove();
    }

    function compressImage(file, maxWidth = 1024, quality = 0.7) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    const scaleSize = maxWidth / img.width;
                    canvas.width = maxWidth;
                    canvas.height = img.height * scaleSize;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(blob => {
                        if (blob) {
                            const compressedFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        } else {
                            reject(new Error("Kompresi gagal"));
                        }
                    }, 'image/jpeg', quality);
                };
                img.onerror = reject;
                img.src = event.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

};
