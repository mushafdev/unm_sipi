
$(document).ready(function () {
    let bedIndex = $('#beds .bed-group').length || 1;

    $('#add-bed').on('click', function () {
        const bedGroup = `
            <div class="row g-2 mb-2 bed-group">
                <div class="col-md-4">
                    <input type="text" name="beds[${bedIndex}][bed_number]" class="form-control" placeholder="Nomor Bed" required>
                </div>
                <div class="col-md-4">
                    <select name="beds[${bedIndex}][status]" class="form-select">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="beds[${bedIndex}][notes]" class="form-control" placeholder="Catatan">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-bed"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        $('#beds').append(bedGroup);
        bedIndex++;
    });

    $(document).on('click', '.remove-bed', function () {
        $(this).closest('.bed-group').remove();
    });
});