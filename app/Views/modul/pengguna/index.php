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
                    Data Pengguna
                    <button id="button-add" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-pengguna">+ Tambah</button>
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
                <table class="table" id="table-pengguna">
                    <thead>
                        <tr class="text-center bg-cyan">
                            <th scope="col">No</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Profil</th>
                            <th scope="col">Status Pegawai</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- modal -->
        <div class="modal" id="modal-pengguna" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- content -->
                    <div class="modal-body">
                         <?php $validation = \Config\Services::validation(); ?>
                        <form id="form-pengguna" method="POST" class="was-validated" action="<?= base_url('upload/do_upload') ?>" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id" id="id" />
                            <?=csrf_field() ?>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select id="jabatan" name="jabatan" class="form-control" required></select>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" required placeholder="Masukan nama pengguna" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" id="email" required placeholder="Masukan email pengguna" />
                            </div>
                            <div class="form-group">
                                <label for="status_pegawai">Status Pegawai</label>
                                <input type="text" class="form-control" name="status_pegawai" id="status_pegawai" required placeholder="Masukan status pegawai pengguna" />
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required placeholder="Masukan password pengguna" />
                                <div class="valid-feedback text-success">Valid</div>
                                <div class="invalid-feedback text-danger"></div>
                            </div>
                            <input type="file" name="profil" accept=".jpg, .jpeg, .png" required="1" />
                            <!-- content -->

                            <!-- button submit -->
                            <button type="submit" class="btn btn-primary" id="button-submit-jabatan" hidden="true"></button>
                            <!-- button submit -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="$('#button-submit-jabatan').click()">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <!-- modal -->

                    <script type="text/javascript">
                        $(document).ready(function () {
                            // minta data jabatan
                            function get_jabatan() {
                                $.get("master/jabatan/data").done((res, xhr, status) => {
                                    const response = jQuery.parseJSON(res);
                                    if (response.status) {
                                        const data = response.data;
                                        if (data) {
                                            // sebelum melakukan proses perulangan array, bersihkan dulu elementnya
                                            $("#jabatan").empty();
                                            // lakukan proses perulangan array
                                            $.each(data, function (index, val) {
                                                $("#jabatan").append(`<option value='${val.id}'>${val.nama}</option>`);
                                            });
                                        }
                                    }
                                });
                            }
                            get_jabatan();

                            // inisialisasi datatable
                            //$("#table-jabatan").DataTable();

                            var table = $("#table-pengguna").DataTable({
                                processing: true,
                                serverSide: true,
                                order: [],
                                ajax: {
                                    url: "<?php echo base_url('/master/pengguna/list') ?>",
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

                            const FormPengguna = $("#form-pengguna");
                            FormPengguna.submit(function (event) {
                                // ketika form disubmit
                                // periksa, apakah ada value id atau tidak
                                // kalau ada, berarti action = edit
                                // kalau gak ada, berarti action = submit
                                let action;

                                const id = $("#id").val();
                                if (id) {
                                    action = "master/pengguna/edit";
                                } else {
                                    action = "master/pengguna/submit";
                                }
                                event.preventDefault();
                                $.ajax({
                                    url: action,
                                    data: new FormData($(this)[0]),
                                    method: "POST",
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                }).done((res, xhr, status) => {
                                    if (res) {
                                        const response = jQuery.parseJSON(res),
                                        status = response.status,
                                        message = response.message;
                                        table.ajax.reload();
                                    }
                                });
                            });

                            // ambil id berdasarkan button yang diklik
                            // hapus
                            $("#table-pengguna").on("click", "#button-delete", function (event) {
                                event.preventDefault();
                                const idValue = $(this).data("id");
                                if (confirm("Apakah Anda yakin ingin menghapus data ini ?")) {
                                    $.post("/master/pengguna/delete", { id: idValue }).done((res, xhr, status) => {
                                        if (res) {
                                            alert("pengguna Berhasil di hapus");
                                            table.ajax.reload();
                                        }
                                    });
                                }
                            });

                            // edit
                            $("#table-pengguna").on("click", "#button-edit", function (event) {
                                event.preventDefault();
                                const idValue = $(this).data("id");
                                $.post("/master/pengguna/get", { id: idValue }).done((res, xhr, status) => {
                                    if (res) {
                                        const response = jQuery.parseJSON(res),
                                        data = response.data;

                                        $("#id").val(data.id);
                                        $("#nama").val(data.nama);
                                        $("#email").val(data.email);
                                        $("#status_pegawai").val(data.status_pegawai);
                                        $("#modal-pengguna").modal("show");
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
</div>
