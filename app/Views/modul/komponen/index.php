<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
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
                    Data Komponen Gaji
                    <button id="button-add" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-komponen">+ Tambah</button>
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
                <table class="table" id="table-komponen">
                    <thead>
                        <tr class="text-center bg-cyan">
                            <th scope="col">No</th>
                            <th scope="col">Pengguna</th>
                            <th scope="col">Jabatan pegawai</th>
                            <th scope="col">Jumlah jam</th>
                            <th scope="col">Gaji pokok</th>
                            <th scope="col">Tunjangan jabatan</th>
                            <th scope="col">Total gaji</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- modal bootstrap -->
        <div class="modal" id="modal-komponen" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Komponen Gaji</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- content -->
                        <div class="modal-body">
                            <?php $validation = \Config\Services::validation(); ?>
                            <form id="form-komponen" method="POST" class="was-validated">
                                <input type="hidden" name="id" id="id" />
                                <?=csrf_field() ?>
                                <div class="form-group">
                                    <label for="pengguna">Pengguna</label>
                                    <select id="pengguna" name="pengguna" class="form-control was-validated" required></select>
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for="jabatan_pegawai">Jabatan pegawai</label>
                                    <input type="text" class="form-control was-validated" name="jabatan_pegawai" id="jabatan_pegawai" placeholder="Masukan Komponen jabatan" required autofocus />
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_jam">Jumlah jam</label>
                                    <input type="text" class="form-control was-validated" name="jumlah_jam" id="jumlah_jam" placeholder="Masukan Komponen jumlah jam" required />
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for="gaji_pokok">Gaji pokok</label>
                                    <input type="text" class="form-control was-validated" name="gaji_pokok" id="gaji_pokok" placeholder="Masukan Komponen gaji pokok" required />
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for="tunjangan_jabatan">Tunjangan jabatan</label>
                                    <input type="text" class="form-control was-validated" name="tunjangan_jabatan" id="tunjangan_jabatan" placeholder="Masukan Komponen tunjangan jabatan" required />
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for="total_gaji">Total gaji</label>
                                    <input type="text" class="form-control was-validated" name="total_gaji" id="total_gaji" placeholder="Masukan Komponen total gaji" required readonly />
                                    <div class="valid-feedback text-success">Valid</div>
                                    <div class="invalid-feedback text-danger"></div>
                                </div>
                                <!-- content -->

                                <!-- button submit -->
                                <button type="submit" class="btn btn-primary" id="button-submit-komponen" hidden="true"></button>
                                <!-- button submit -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="$('#button-submit-komponen').click()">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal bootstrap -->

        <script type="text/javascript">
            $(document).ready(function () {
                
                $("#tunjangan_jabatan").on("keyup", function (event) {
                    event.preventDefault();
                    let result = parseFloat($("#gaji_pokok").val()) + parseFloat($("#tunjangan_jabatan").val());
                    $("#total_gaji").val(result);
                });

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

                var table = $("#table-komponen").DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                    'excel', 'pdf', 'print'
                    ],
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?php echo base_url('/penggajian/komponen/list') ?>",
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

                const FormKomponen = $("#form-komponen");
                FormKomponen.submit(function (event) {
                    // ketika form disubmit
                    // periksa, apakah ada value id atau tidak
                    // kalau ada, berarti action = edit
                    // kalau gak ada, berarti action = submit
                    let action;

                    const id = $("#id").val();
                    if (id) {
                        action = "penggajian/komponen/edit";
                    } else {
                        action = "penggajian/komponen/submit";
                    }
                     if(confirm("Apakah data yang diinput sudah benar ?")) 
                    event.preventDefault();
                    $.post(action, FormKomponen.serialize()).done((res, xhr, status) => {
                        if (res) {
                            alert("pengguna berhasil diperbarui");
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
                                alert("Data berhasil diinput");
                            }
                        }
                    });
                });

                // ambil id berdasarkan button yang diklik
                // hapus
                $("#table-komponen").on("click", "#button-delete", function (event) {
                    event.preventDefault();
                    const idValue = $(this).data("id");
                    if (confirm("Apakah Anda yakin ingin menghapus data ini ?")) {
                        $.post("/penggajian/komponen/delete", { id: idValue }).done((res, xhr, status) => {
                            if (res) {
                                alert("Komponen Gaji Berhasil di hapus");
                                table.ajax.reload();
                            }
                        });
                    }
                });
                // detail
                // $("#table-komponen").on("click", "#button-detail", function (event) {
                //     event.preventDefault();
                //     const idValue = $(this).data("id");
                //     $.post("/penggajian/komponen/detail", { id: idValue }).done((res, xhr, status) => {
                //         if (res) {
                //             const response = jQuery.parseJSON(res),
                //             data = response.data;

                //             // set id
                //             $("#id").val(data.id);
                //             $("#jabatan_pegawai").val(data.jabatan_pegawai);
                //             $("#jumlah_jam").val(data.jumlah_jam);
                //             $("#gaji_pokok").val(data.gaji_pokok);
                //             $("#tunjangan_jabatan").val(data.tunjangan_jabatan);
                //             $("#total_gaji").val(data.total_gaji);
                //             $("#modal-detail-komponen").modal("show");
                //         }
                //     });
                // });

                $("#table-komponen").on("click", "#button-edit", function (event) {
                    event.preventDefault();
                    const idValue = $(this).data("id");
                    $.post("/penggajian/komponen/get", { id: idValue }).done((res, xhr, status) => {
                        if (res) {
                            const response = jQuery.parseJSON(res),
                            data = response.data;

                            // set id
                            $("#id").val(data.id);
                            $("#pengguna").val(data.id_pengguna);
                            $("#jabatan_pegawai").val(data.jabatan_pegawai);
                            $("#jumlah_jam").val(data.jumlah_jam);
                            $("#gaji_pokok").val(data.gaji_pokok);
                            $("#tunjangan_jabatan").val(data.tunjangan_jabatan);
                            $("#total_gaji").val(data.total_gaji);
                            $("#modal-komponen").modal("show");
                        }
                    });
                });
            });
        </script>
    </section>
</div>
