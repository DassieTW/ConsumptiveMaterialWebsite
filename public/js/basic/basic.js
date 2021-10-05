var table = $.session.get('basic');

var choose = "FactoryExample";
$(document).ready(function() {
    $("#download").attr("href","../download/FactoryExample.xlsx");
    $('.nav-item').on('click', function() {
        choose = this.id;
        $("#download").attr("href","../download/"+choose+".xlsx");
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#basicdata').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var check = [];
    var data = [];
    var olddata = [];
    var select = ($(document.activeElement).val());

    if(choose == "FactoryExample")
    {
        var datacheck = "factorycheck";
        var dataname = "factory";
        var datanew = "factorynew";
    }
    if(choose == "ClientExample")
    {
        var datacheck = "clientcheck";
        var dataname = "client";
        var datanew = "clientnew";
    }
    if(choose == "MachineExample")
    {
        var datacheck = "machinecheck";
        var dataname = "machine";
        var datanew = "machinenew";
    }
    if(choose == "ProductionExample")
    {
        var datacheck = "productioncheck";
        var dataname = "production";
        var datanew = "productionnew";
    }
    if(choose == "LineExample")
    {
        var datacheck = "linecheck";
        var dataname = "line";
        var datanew = "linenew";
    }
    if(choose == "UseExample")
    {
        var datacheck = "usecheck";
        var dataname = "use";
        var datanew = "usenew";
    }
    if(choose == "UseReasonExample")
    {
        var datacheck = "usereasoncheck";
        var dataname = "usereason";
        var datanew = "usereasonnew";
    }
    if(choose == "InReasonExample")
    {
        var datacheck = "inreasoncheck";
        var dataname = "inreason";
        var datanew = "inreasonnew";
    }
    if(choose == "PositionExample")
    {
        var datacheck = "positioncheck";
        var dataname = "position";
        var datanew = "positionnew";
    }
    if(choose == "SendExample")
    {
        var datacheck = "sendcheck";
        var dataname = "send";
        var datanew = "sendnew";
    }
    if(choose == "OboundExample")
    {
        var datacheck = "ocheck";
        var dataname = "o";
        var datanew = "onew";
    }
    if(choose == "BackReasonExample")
    {
        var datacheck = "backcheck";
        var dataname = "back";
        var datanew = "backnew";
    }

    $("input:checkbox[name="+datacheck+"]:checked").each(function(){
        check.push($(this).val());
    });

    if(select == "刪除" || select == "删除" || select == "Delete")
    {
        select = "刪除";
    }
    if(select == "更新" || select == "Update")
    {
        select = "更新";
    }

    var count = check.length;

    for(let i = 0 ; i < count ; i++)
    {
        data.push($("#"+dataname + check[i]).val());
        olddata.push($("#old"+dataname + check[i]).val());
    }
    datanew = ($("#"+datanew).val());

    checked = $("input:checkbox[name="+datacheck+"]:checked").length;

    if(!checked) {
        alert(Lang.get('monthlyPRpageLang.nocheck'));
        return false;
    }
    console.log(olddata);
    console.log(dataname);
    console.log(data);

    $.ajax({
        type: 'POST',
        url: "basic/changeordelete",
        data: {
            select : select ,data : data , datanew : datanew , dataname : dataname, olddata : olddata
        },

        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $('body').loadingModal({
                text: 'Loading...',
                animation: 'circle'
            });
        },
        complete: function () {
            $('body').loadingModal('hide');
        },
        success: function (data) {
            console.log(data);
            var myObj = JSON.parse(data);
            console.log(myObj);
            if (myObj.boolean === true) {
                var mess = Lang.get('basicInfoLang.change') + ' / ' + Lang.get('basicInfoLang.delete') + ' ' + Lang.get('basicInfoLang.success');
                alert(mess);
                window.location.href('/basic?tabname=' + myObj.database);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.warn(jqXHR.responseText);
            alert(errorThrown);
        }

    });
});
