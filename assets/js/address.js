$(document).ready(function () {
  let temp;
  $("#province").select2({
    placeholder: "Pilih Provinsi",
    language: "id",
  });
  $("#city").select2({
    placeholder: "Pilih Kota/Kabupaten",
    language: "id",
  });
  $("#district").select2({
    placeholder: "Pilih Kecamatan",
    language: "id",
  });
  $("#village").select2({
    placeholder: "Pilih Kelurahan",
    language: "id",
  });
  $("#province").change(function () {
    var id_provinces = $(this).val();
    $.ajax({
      type: "GET",
      url:
        "http://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=" +
        id_provinces,
      dataType: "json",
      success: function (data) {
        $("#city").empty();
        $("#city").append('<option value="">Pilih Kota/Kabupaten</option>');
        $.each(data.kota_kabupaten, function (i, item) {
          $("#city").append(
            '<option value="' + item.id + '">' + item.nama + "</option>"
          );
          // console.log(item.id)
        });
      },
    });
  });

  $("#city").change(getAjaxKota);
  function getAjaxKota() {
    var id_city = $(this).val();
    $.ajax({
      type: "GET",
      url:
        "http://dev.farizdotid.com/api/daerahindonesia/kecamatan?id_kota=" +
        id_city,
      dataType: "json",
      success: function (data) {
        $("#district").empty();
        $("#district").append('<option value="">Pilih Kecamatan</option>');
        $.each(data.kecamatan, function (i, item) {
          $("#district").append(
            '<option value="' + item.id + '">' + item.nama + "</option>"
          );
          // console.log(item.id)
        });
      },
    });
  }

  $("#district").change(getAjaxKecamatan);
  function getAjaxKecamatan() {
    // console.log("cliked");
    var id_district = $(this).val();
    $.ajax({
      type: "GET",
      url:
        "https://dev.farizdotid.com/api/daerahindonesia/kelurahan?id_kecamatan=" +
        id_district,
      dataType: "json",
      success: function (data) {
        // console.log(data)
        $("#village").empty();
        $("#village").append('<option value="">Pilih Kelurahan</option>');
        $.each(data.kelurahan, function (i, item) {
          $("#village").append(
            '<option value="' + item.id + '">' + item.nama + "</option>"
          );
          // console.log(item.id)
        });
      },
    });
  }
  $("#village").change(getSelected);
  function getSelected() {
    $("#select2-province-container").each(function () {
      temp = $(this).text();
    });

    $("#select2-city-container").each(function () {
      temp += "|" + $(this).text();
    });

    $("#select2-district-container").each(function () {
      temp += "|" + $(this).text();
    });

    $("#select2-village-container").each(function () {
      temp += "|" + $(this).text();
      $("#selectedAll").val(temp);
    });
  }
});
