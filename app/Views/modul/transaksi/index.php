<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css" />
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Data Transaksi Gaji
                    <button id="button-add" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-transaksi">+ Tambah</button>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="table-transaksi">
                    <thead>
                        <tr class="text-center bg-cyan">
                            <th scope="col">No</th>
                            <th scope="col">Pengguna</th>
                            <th scope="col">Tanggal transaksi</th>
                            <th scope="col">Jumlah transaksi</th>
                            <th scope="col">Keterangan transaksi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- modal bootstrap -->
        <div class="modal" id="modal-transaksi" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php $validation = \Config\Services::validation(); ?>
                        <form id="form-transaksi" method="POST" class="was-validated">
                            <input type="hidden" name="id" id="id" />
                            <?=csrf_field() ?>
                            <!-- content -->
                            <div class="form-group">
                                <label for="pengguna">pengguna</label>
                                <select id="pengguna" name="pengguna" class="form-control" required></select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_transaksi">tanggal transaksi</label>
                                <input type="date" class="form-control" name="tanggal_transaksi" id="tanggal_transaksi" required placeholder="Masukan tanggal transaksi" />
                            </div>
                            <div class="form-group">
                                <label for="jumlah_transaksi">jumlah transaksi</label>
                                <input type="text" class="form-control" name="jumlah_transaksi" id="jumlah_transaksi" required placeholder="Masukan jumlah transaksi" maxlength="15" />
                            </div>
                            <div class="form-group">
                                <label for="keterangan_transaksi">keterangan transaksi</label>
                                <input type="text" class="form-control" name="keterangan_transaksi" id="keterangan_transaksi" required placeholder="Masukan keterangan transaksi" />
                                <div class="valid-feedback text-success">Valid</div>
                                <div class="invalid-feedback text-danger"></div>
                            </div>
                            <!-- content -->

                            <!-- button submit -->
                            <button type="submit" class="btn btn-primary" id="button-submit-transaksi" hidden="true"></button>
                            <!-- button submit -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="$('#button-submit-transaksi').click()">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal bootstrap -->
    </section>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo CLIENT_KEY_MIDTRANS_SANDBOX ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // minta data pengguna
        function get_pengguna() {
            $.get("master/pengguna/data").done((res, xhr, status) => {
                const response = jQuery.parseJSON(res);
                if (response.status) {
                    const data = response.data;
                    if (data) {
                        // sebelum melakukan proses perulangan array, bersihkan dulu elementnya
                        $("#pengguna").empty();
                        // lakukan proses perulangan array
                        $.each(data, function (index, val) {
                            $("#pengguna").append(`<option value='${val.id}'>${val.nama}</option>`);
                        });
                    }
                }
            });
        }
        get_pengguna();

        // inisialisasi datatable
        //$("#table-jabatan").DataTable();

        var table = $("#table-transaksi").DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?php echo base_url('/penggajian/transaksi/list') ?>",
                type: "POST",
            },
            columnDefs: [
                {
                    targets: [],
                    orderable: false,
                },
            ],
            pageLength: 5,
        });

        function formatRupiah(angka) {
            var reverse = angka.toString().split("").reverse().join("");
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join(".").split("").reverse().join("");
            return "Rp. " + ribuan;
        }

        $("#jumlah_transaksi").on("input", function (event) {
            const value = $(this).val();
            const sanitizedValue = value.replace(/[^\d]/g, ""); // Menghapus karakter selain angka
            const formattedValue = formatRupiah(sanitizedValue);
            $(this).val(formattedValue);
        });

        const FormTransaksi = $("#form-transaksi");
        FormTransaksi.submit(function (event) {
            event.preventDefault();

            // ketika form disubmit
            // periksa, apakah ada value id atau tidak
            // kalau ada, berarti action = edit
            // kalau gak ada, berarti action = submit
            let action;

            const id = $("#id").val();
            if (id) {
                action = "penggajian/transaksi/edit";
            } else {
                action = "penggajian/transaksi/submit";
            }

            if (confirm("Apakah data yang diinput sudah benar ?")) {
                $.post(action, FormTransaksi.serialize()).done((res, xhr, status) => {
                    if (res) {
                        table.ajax.reload();

                        // parse json menggunakan jQuery.parseJSON(res) -> supaya bisa digunakan. Lihat response
                        let response = jQuery.parseJSON(res),
                            status = response.status,
                            message = response.message;

                        // jika status false
                        if (!status) {
                            // list error dinamis: jumlah berdasarkan error
                            const errors = message.errors;
                            $.each(errors, function (index, val) {
                                alert(val);
                                $(`#${index}`).val("");
                            });
                        } else {
                            // alert("Data berhasil diinput");
                            let token = response.data;
                            if (token) {
                                snap.pay(token, {
                                    onSuccess: function (result) {
                                        console.log(result);
                                        // redirect user to success page
                                    },
                                    onPending: function (result) {
                                        console.log(result);
                                        // redirect user to pending page
                                    },
                                    onError: function (result) {
                                        console.error(result);
                                        // redirect user to error page
                                    },
                                });
                            }
                        }
                    }
                });
            }
        });

        // ambil id berdasarkan button yang diklik
        // hapus
        $("#table-transaksi").on("click", "#button-delete", function (event) {
            event.preventDefault();
            const idValue = $(this).data("id");
            if (confirm("Apakah Anda yakin ingin menghapus data ini ?")) {
                $.post("/penggajian/transaksi/delete", { id: idValue }).done((res, xhr, status) => {
                    if (res) {
                        alert("Transaksi Gaji Berhasil di hapus");
                        table.ajax.reload();
                    }
                });
            }
        });
        //detail
    

        // edit
        $("#table-transaksi").on("click", "#button-edit", function (event) {
            event.preventDefault();
            const idValue = $(this).data("id");
            $.post("/penggajian/transaksi/get", { id: idValue }).done((res, xhr, status) => {
                if (res) {
                    const response = jQuery.parseJSON(res),
                        data = response.data;

                    // set id
                    $("#id").val(data.id);
                    $("#tanggal_transaksi").val(data.tanggal_transaksi);
                    $("#jumlah_transaksi").val(data.jumlah_transaksi);
                    $("#keterangan_transaksi").val(data.keterangan_transaksi);
                    $("#modal-transaksi").modal("show");
                }
            });
        });
    });
</script>
