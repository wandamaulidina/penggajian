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
                    Data Laporan Gaji
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
                <table class="table" id="table-laporan">
                    <thead>
                        <tr class="text-center bg-cyan">
                            <th scope="col">NO</th>
                            <th scope="col">Nama Jabatan</th>
                            <th scope="col">Nama Pengguna</th>
                            <th scope="col">Jumlah Jam</th>
                            <th scope="col">Gaji Pokok</th>
                            <th scope="col">Tunjangan Jabatan</th>
                            <th scope="col">Total Gaji</th>
                            <!-- <th scope="col">Tanggal Transaksi</th> -->
                            <!-- <th scope="col">jumlah Transaksi</th> -->
                            <!-- <th scope="col">Keterangan Transaksi</th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#tunjangan_jabatan").on("keyup", function (event) {
                    event.preventDefault();
                    let result = parseFloat($("#gaji_pokok").val()) + parseFloat($("#tunjangan_jabatan").val());
                    $("#total_gaji").val(result);
                });

                // minta data pengguna
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
                function get_komponen() {
                    $.get("penggajian/komponen/data").done((res, xhr, status) => {
                        const response = jQuery.parseJSON(res);
                        if (response.status) {
                            const data = response.data;
                            if (data) {
                                // sebelum melakukan proses perulangan array, bersihkan dulu elementnya
                                $("#komponen").empty();
                                // lakukan proses perulangan array
                                $.each(data, function (index, val) {
                                    $("#komponen").append(`<option value='${val.id}'>${val.jumlah_jam}>${val.gaji_pokok}>${val.tunjangan_jabatan}>${val.total_gaji}
                                        </option>`);
                                });
                            }
                        }
                    });
                }
                function get_transaksi() {
                    $.get("penggajian/transaksi/data").done((res, xhr, status) => {
                        const response = jQuery.parseJSON(res);
                        if (response.status) {
                            const data = response.data;
                            if (data) {
                                // sebelum melakukan proses perulangan array, bersihkan dulu elementnya
                                $("#transaksi").empty();
                                // lakukan proses perulangan array
                                $.each(data, function (index, val) {
                                    $("#transaksi").append(`<option value='${val.id}'>${val.tanggal_transaksi}>${val.jumlah_transaksi}>${val.keterangan_transaksi}</option>`);
                                });
                            }
                        }
                    });
                }
                get_pengguna();

                // inisialisasi datatable
                //$("#table-jabatan").DataTable();

                var table = $("#table-laporan").DataTable({
                    dom: "Bfrtip",
                    buttons: ["excel", "pdf", "print"],
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?php echo base_url('/laporan/list') ?>",
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
            });
        </script>
    </section>
</div>