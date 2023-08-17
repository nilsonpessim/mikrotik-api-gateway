//CARREGA OS SCRIPTS
$(document).ready(function(){

    showDataTables([
        "#tbApi",
        "#tbUser",
        "#tbMikroTik"
    ]);

    // STATUS API USER PAGE
    if (typeof $('#api_edit_form').val() !== "undefined") {
        $.ajax({
            type: 'POST',
            url: '/view/ajax/getApi.php',
            data: {
                'token': $('#csrf').val(),
                'idApi': $('#api_edit_id').val()
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == '1') {
                    $( "#api_edit_status" ).prop("checked",true);
                }
            },
            error: function(){
                console.log('NÃO FOI POSSÍVEL COMPLETAR A SUA SOLICITAÇÃO');
            }
        });
    }

    // MIKROTIK USER
    if (typeof $('#api_mikrotik_form').val() !== "undefined") {
        $.ajax({
            type: 'POST',
            url: '/view/ajax/getApiMikroTik.php',
            data: {
                'token': $('#csrf').val(),
                'idApi': $('#api_mikrotik_id').val()
            },
            dataType: 'json',
            success: function(data) {
                for (let i = 0; i < data.length; i++) {
                    $.each(data[i], function(key,val) {
                        $("#"+key).prop("checked",val);
                    })
                }
            }
        });
    }

    // STATUS USER PAGE
    if (typeof $('#user_edit_form').val() !== "undefined") {
        $.ajax({
            type: 'POST',
            url: '/view/ajax/getUser.php',
            data: {
                'token': $('#csrf').val(),
                'idUser': $('#user_edit_id').val()
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == '1') {
                    $( "#user_edit_status" ).prop("checked",true);
                }
            },
            error: function(){
                console.log('NÃO FOI POSSÍVEL COMPLETAR A SUA SOLICITAÇÃO');
            }
        });
    }

    // STATUS MIKROTIK
    if (typeof $('#mikrotik_edit_form').val() !== "undefined") {

        $.ajax({
            type: 'POST',
            url: '/view/ajax/getMikroTik.php',
            data: {
                'token': $('#csrf').val(),
                'idMikroTik': $('#mikrotik_edit_id').val()
            },
            dataType: 'json',
            success: function(data) {

                if (data.status == '1') {
                    $( "#mikrotik_edit_status" ).prop("checked",true);
                }

            },
            error: function(){
                console.log('NÃO FOI POSSÍVEL COMPLETAR A SUA SOLICITAÇÃO');
            }
        });
    }

});

//DATATABLE
function showDataTables(nameTable=['#exatbUsermple'], position=0, order='asc', qnt=25){

    nameTable.forEach(name => {
        $(name).DataTable({
            "language": {
                "url": '/view/assets/json/datatables.json'
            }, 
            "order": [[position, order]],
            "pageLength": qnt
        });
    });

}