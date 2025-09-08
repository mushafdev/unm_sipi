var form = $("#form");
var urlStore = form.data('store');
var urlDetail = form.data('detail');
var urlUpdate = form.data('update');
var urlIndex = form.data('index');

// Validasi real-time
$('.form-control').on('keyup change', function () {
    $(this).parsley().validate();
});

// Inisialisasi AutoNumeric
const autoNumericOptions = {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 0,
    unformatOnSubmit: true,
    modifyValueOnWheel: false,
    allowNegative: true
};

AutoNumeric.multiple('.autonum', autoNumericOptions);

// Hitung selisih penjualan
$('.total_aktual').on('keyup change', function () {
    const index = $(this).data('index');

    const $system = $(`.total_system[data-index="${index}"]`);
    const $aktual = $(`.total_aktual[data-index="${index}"]`);
    const $selisih = $(`.selisih[data-index="${index}"]`);

    if (
        AutoNumeric.isManagedByAutoNumeric($system[0]) &&
        AutoNumeric.isManagedByAutoNumeric($aktual[0])
    ) {
        const systemVal = AutoNumeric.getNumber($system[0]) || 0;
        const aktualVal = AutoNumeric.getNumber($aktual[0]) || 0;
        const selisih = aktualVal - systemVal;

        const anSelisih = AutoNumeric.getAutoNumericElement($selisih[0]) ||
            new AutoNumeric($selisih[0], autoNumericOptions);
        anSelisih.set(selisih);
    }
});

// Hitung selisih kas (penerimaan & pengeluaran)
$('#kas-table').on('input', 'input.total_aktual', function () {
    const $row = $(this).closest('tr');

    const $incomeSystemEl   = $row.find('input[name="kas_income[]"]');
    const $expenseSystemEl  = $row.find('input[name="kas_expense[]"]');
    const $incomeAktualEl   = $row.find('input[name="kas_income_aktual[]"]');
    const $expenseAktualEl  = $row.find('input[name="kas_expense_aktual[]"]');
    const $incomeSelisihEl  = $row.find('input[name="kas_income_selisih[]"]');
    const $expenseSelisihEl = $row.find('input[name="kas_expense_selisih[]"]');

    // Hitung dan set income selisih
    if (
        AutoNumeric.isManagedByAutoNumeric($incomeSystemEl[0]) &&
        AutoNumeric.isManagedByAutoNumeric($incomeAktualEl[0])
    ) {
        const incomeSystem = AutoNumeric.getNumber($incomeSystemEl[0]) || 0;
        const incomeAktual = AutoNumeric.getNumber($incomeAktualEl[0]) || 0;
        const incomeSelisih = incomeAktual - incomeSystem;

        const anIncomeSelisih = AutoNumeric.getAutoNumericElement($incomeSelisihEl[0]) ||
            new AutoNumeric($incomeSelisihEl[0], autoNumericOptions);
        anIncomeSelisih.set(incomeSelisih);
    }

    // Hitung dan set expense selisih
    if (
        AutoNumeric.isManagedByAutoNumeric($expenseSystemEl[0]) &&
        AutoNumeric.isManagedByAutoNumeric($expenseAktualEl[0])
    ) {
        const expenseSystem = AutoNumeric.getNumber($expenseSystemEl[0]) || 0;
        const expenseAktual = AutoNumeric.getNumber($expenseAktualEl[0]) || 0;
        const expenseSelisih = expenseAktual - expenseSystem;

        const anExpenseSelisih = AutoNumeric.getAutoNumericElement($expenseSelisihEl[0]) ||
            new AutoNumeric($expenseSelisihEl[0], autoNumericOptions);
        anExpenseSelisih.set(expenseSelisih);
    }
});

// Tombol simpan dengan AJAX
$("#save").on('click', function (e) {
    e.preventDefault();

    if ($("#form").parsley().validate()) {
        var param = $(this);
        var formData = new FormData(form[0]);

        $.ajax({
            type: 'POST',
            url: urlStore,
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            async: true,
            beforeSend: function () {
                process(param);
            },
            success: function (data) {
                processDone(param, 'bi bi-save', ' Simpan');
                Swal.fire({
                    title: "Success!",
                    text: data.text,
                    icon: "success",
                    timer: 2000
                }).then(() => {
                    location.replace(urlIndex + '/' + data.id);
                });
            },
            error: function (reject) {
                processDone(param, 'bi bi-save', ' Simpan');
                $('#form').parsley().reset();

                if (reject.status === 422) {
                    handleParsleyErrors(reject.responseJSON.errors);
                } else {
                    Swal.fire("Error", "Terjadi kesalahan sistem", "error");
                }
            }
        });
    }
});
