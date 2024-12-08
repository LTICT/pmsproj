  $(document).ready(function() {
      $(".mobile-toggle-nav").click(function(){$(this).toggleClass("is-active");
      $(".app-container").toggleClass("sidebar-mobile-open");
    });
    $(".mobile-toggle-header-nav").click(function(){
      $(this).toggleClass("active");
      $(".app-header__content").toggleClass("header-mobile-open")
    });
    $("body").on('keyup','#search', function(event) {
      event.preventDefault();
      if (event.keyCode === 13) {
        $("#searchButton").click();
      }
    });
    //START PAYMENT SLIP
    $('body').on('click','#paymentReport', function () {
     $('#modal-default').modal('show');
     $('.modal-content').addClass("custom-modal-width");
     $('#modal-title').html("");
     $('#modal-body').html("");
     $.ajax({
      url: "paymentform",
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      dataType: "json",
      type: "POST",
      data:{doc_month:$("#doc_month").val(),doc_year:$("#doc_year").val(),reportType:$("#reportType").val(),pageType:"Ajax"}
    }).done(function (response) {
      $('#modal-title').html(response.pageTitle);
      $('#modal-body').html(response.form);
    });
  });
//END PAYMENT SLIP
$("form").submit(function(){
  $("#main-page").data('changed', false);
  $(this).find('button').prop("disabled", true);
  $(this).find('button').text("Processing ...");
});
$(".alert").delay(7000).fadeOut(3000);
$(".close-sidebar-btn").click(function(){
  var t=$(this).attr("data-class");
  $(".app-container").toggleClass(t);
  var n=$(this);n.hasClass("is-active")?n.removeClass("is-active"):n.addClass("is-active")
});
        //START RIGHT BAR
        $(".btn-open-options").click(function(){
          $(".ui-theme-settings").toggleClass("settings-open");
        });
        //END RIGHT BAR
    //START EXPORT TO EXCELL
  $("body").on('click','#exportToExcell', function() {
            //e.preventDefault();
            $(".btn-toolbar").remove();
            $(".exportable").tableExport({formats: ["xlsx","csv"]});
            $(".pvtTable").tableExport({formats: ["xlsx","csv"]});
            $(".jsgrid").tableExport({formats: ["xlsx","csv"]});
          }); 
        //END EXPORT TO EXCELL
      });
  $('body').on('click','#print', function() {
    $("#modal-body").empty();
    window.print();
  });
  $('body').on('click','#printPopUp', function() {
    window.print();
    $("#modal-body").empty();
    $('#modal-default').modal('hide');
  });
  $("body").on('change','input,textarea', function() {
    $(this).closest('#main-page').data('changed', true);
  });
  $("body").on('click','a', function() {
    var clickedLink=$(this).closest('a');
    $(document)
    .ajaxStart(function () {
      clickedLink.addClass('dis-link');
      $(".mn-loader").removeClass("hidden");
    })
    .ajaxStop(function (result) {
      clickedLink.removeClass('dis-link');
      $(".mn-loader").addClass("hidden");
    });
  });
  //START LEAVE END DATE
        $("body").on('click','#btnCalculateLeave', function() {
          $.ajax({
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           url: "employeeleave/calculatereturndate",
           data:{emp_id:$("#master_id").val(),start_date:$("#eml_start_date").val(), end_date:$("#eml_end_date").val(), eml_leave_type_id:$("#eml_leave_type_id").val(),
           leave_id:$("#leave_id").val(),
           eml_days:$("#eml_day_number").val()},
           type:"POSt",
           dataType: "json"
         }).done(function(response) {
           $("#eml_end_date").val(response.end_date);
           $("#eml_returned_date").val(response.return_date);
            $("#availablebalance").html(response.available_days);
         });
       });
       $('#btnLoadNote').click(function() {
        $('#note-cont3').html("");
        $('#note-info3').html("");
        var selectedLan=$(this).find('a').html();
        $.ajax({
            url: "note/getnotelist",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:{selectedlan:selectedLan}
        }).done(function(response) {
            $('#note-info3').html(response.page_info);
            $('#note-cont3').html(response.form);
        });
    });
    $("body").on('click','#btnLoadLeave', function() {
        $('#note-cont4').html("");
        $('#note-info4').html("");
        var selectedLan=$(this).find('a').html();
        $.ajax({
            url: "employeeleave/getlistform",
            type: "POST",
            dataType: "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:{selectedlan:selectedLan}
        }).done(function(response) {
            $('#note-info4').html(response.page_info);
            $('#note-cont4').html(response.form);
        });
    });
        //END LEAVE END DATE
  $(window).on('load', function() {
   $(".mn-loader").addClass("hidden");
 });