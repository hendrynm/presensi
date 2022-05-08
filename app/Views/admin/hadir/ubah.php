<?= $this->extend("layout/master-admin") ?>

<?= $this->section("title") ?>
Admin HMSI | Hadir | Ubah
<?= $this->endSection() ?>

<?= $this->section("halaman") ?>
Ubah Pranala Kehadiran Acara
<?= $this->endSection() ?>

<?= $this->section("konten") ?>

<form action="<?= base_url("admin/hadir/ubah") ?>" method="post" data-parsley-validate>
    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <label for="kode_acara" class="tx-bold">Kode <span class="tx-danger">*</span></label>
                <input id="kode_acara" name="kode_acara" type="text" class="form-control" readonly value="<?= $data->kode_acara ?>">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="nama_acara" class="tx-bold">Nama Acara <span class="tx-danger">*</span></label>
                <input id="nama_acara" name="nama_acara" type="text" class="form-control" placeholder="Masukkan nama acara" required data-parsley-required-message="Nama Acara wajib diisi!" value="<?= $data->nama_acara ?>">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="tanggal" class="tx-bold">Tanggal <span class="tx-danger">*</span></label>
                <input id="tanggal" name="tanggal" type="datetime-local" class="form-control" placeholder="Masukkan tanggal acara" required data-parsley-required-message="Tanggal Acara wajib diisi!" value="<?= str_replace(' ', 'T', $data->tanggal) ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="form-group">
                <label for="lokasi" class="tx-bold">Lokasi <span class="tx-danger">*</span></label>
                <input id="lokasi" name="lokasi" type="text" class="form-control" placeholder="Masukkan lokasi acara (dapat berupa link online meet atau lokasi offline)" required data-parsley-required-message="Lokasi Acara wajib diisi!" value="<?= $data->lokasi ?>">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="tipe" class="tx-bold">Tipe Acara <span class="tx-danger">*</span></label>
                <select id="tipe" name="tipe" type="text" class="form-control" required data-parsley-required-message="Tipe Acara wajib diisi!">
                    <option value="0" <?= ($data->tipe === '0') ? "selected" : "" ?>>0 - WAJIB diikuti fungsionaris</option>
                    <option value="1" <?= ($data->tipe === '1') ? "selected" : "" ?>>1 - TIDAK WAJIB diikuti fungsionaris</option>
                    <option value="2" <?= ($data->tipe === '2') ? "selected" : "" ?>>2 - Hanya diikuti fungsionaris TERTENTU</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label for="narahubung1" class="tx-bold">Narahubung <span class="tx-danger">*</span></label>
                <select id="narahubung1" name="narahubung1" class="form-control" required data-parsley-required-message="Narahubung wajib diisi!">
                    <option value="<?= $data->narahubung ?>" selected><?= $data->nama . " | " . $data->nrp ?></option>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="no_wa1" class="tx-bold">Nomor WhatsApp <span class="tx-danger">*</span></label>
                <input id="no_wa1" name="no_wa1" type="text" class="form-control" placeholder="Masukkan bagian harahubung" readonly value="<?= $data->no_wa ?>">
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="id_line1" class="tx-bold">ID LINE <span class="tx-danger">*</span></label>
                <input id="id_line1" name="id_line1" type="text" class="form-control" placeholder="Masukkan bagian harahubung" readonly value="<?= $data->id_line ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label class="tx-italic tx-danger tx-bold">Catatan Penting: </label>
            <label class="tx-italic tx-danger">1. Jika Nomor WA dan ID Line tidak muncul setelah memasukkan nama, berarti narahubung tersebut belum melengkapi profilnya. Silakan melengkapi profil terlebih dahulu.</label>
            <label class="tx-italic tx-danger">2. Narahubung yang tercantum hanya bisa satu orang saja.</label>
            <label class="tx-italic tx-danger">3. Tipe acara secara umum adalah acara tidak wajib. Konfirmasi kepada Vice Head jika mengajukan acara menjadi wajib.</label>
        </div>
        <div class="col-lg-3 mt-auto">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary btn-icon">
                    <i data-feather="save"></i> <span>Simpan Data</span>
                </button>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section("js") ?>

<script>
    $('#tipe').select2();

    let narahubung1 = $("#narahubung1");
    narahubung1.select2({
        placeholder: "Masukkan nama narahubung",
        ajax: {
            type: "GET",
            url: "/ajax/cek_narahubung/",
            dataType: "json",
            delay: 1000,
            minimumInputLength: 3,

            data: function (term) {
                return {
                    key: term.term
                };
            },

            processResults: function (data) {
                console.log(data);
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama + " | " + item.nrp,
                            id: item.id_pengurus,
                        }
                    })
                };
            },
        }
    });

    $(".select2-container").addClass("tx-12");

    narahubung1.on("change", function (){
        $.ajax({
            type: "GET",
            url: "/ajax/cek_kontak/" + narahubung1.val(),
            dataType: "json",

            success: function (data)
            {
                console.log(data);
                document.getElementById("no_wa1").value = data.no_wa;
                document.getElementById("id_line1").value = data.id_line;
            }
        });
    });
</script>

<?= $this->endSection() ?>
