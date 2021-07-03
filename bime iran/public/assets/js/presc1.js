var contract_f = 30;
var permision = 1;
var expire=0;
var baghiha=[];
var inprescs=[];
var register=0;
var send_to_expert=0;
var ticket=0;
var hi=1;
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var timer;

$(document).ready(function() {

    var max_fields = 100;
    //maximum input boxes allowed
    var wrapper = $("#table");
    //Fields wrapper
    $(".add_row").click(function(e) {
        $(wrapper).append('<tr id="tr' + hike + '"><td class="col-sm-2"><input name="med_spec['+hike+']" id="med_spec'+hike+'" type="text" value="0" hidden/><input name="number['+hike+']" type="text" value="'+hike+'" hidden="hidden" /><input id="medicineid'+hike+'" name="medicine[' + hike + ']" type="text" value="0" hidden="hidden" /> <input type="text"  class="form-control" name="medicine_name['+ hike +']" id="medicine' + hike + '" onkeydown="find_med(' + hike + ')"  style=" "> <div class="dropdown" id="dropsearch' + hike + '"> <span class=" dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> </span> <ul class="dropdown-menu search-result" id="med_find_result' + hike + '"  style="width: 100%;text-align: right;"> </ul> </div> <div id="medicine_spec' + hike + '"> </div></td >  <td ><input name="count[' + hike + ']" class="form-control" id="count' + hike + '" style="border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;border-bottom: none" onchange="myChange(' + hike + ')" > <div class="input-group " style="direction: ltr"><input name="unit[' + hike + ']" class="form-control myinput" id="unit' + hike + '"  ><div  class="input-group-addon" style="border-top-right-radius: 0px">واحد</div></div></td> <td ><div class="input-group " style="direction: ltr"> <input name="order_per_hour[' + hike + ']" class="form-control" id="order_per_hour' + hike + '" style="border-bottom: none;border-bottom-left-radius:0px" > <select name="hour[' + hike + ']" class="form-control"  style="border-bottom-left-radius: 4px" > <option value="ساعت">ساعت</option> <option value="روز">روز</option> </select> <div  class="input-group-addon" >هر</div> </div> </td> <td><input name="price[' + hike + ']" class="form-control" id="price' + hike + '" ></td> <td><input name="open_market[' + hike + ']" class="form-control" id="open_market' + hike + '" onchange="open_market(' + hike + ')" ></td> <td><input name="others[' + hike + ']" class="form-control" id="others' + hike + '" onchange="others(' + hike + ')"></td> <td > <div  class="input-group-addon myaddon " id="fp' + hike + '">%0</div> <input name="first_insure_percent[' + hike + ']" class="form-control myinput" id="first_insure_percent' + hike + '" onchange="calculate_sum()"> </td> <td ><div  class="input-group-addon myaddon" id="ip' + hike + '">%0</div> <input name="iran_percent[' + hike + ']" class="form-control myinput" id="iran_percent' + hike + '" onchange="calculate_sum()"> </td> <td ><div  class="input-group-addon myaddon" id="pp' + hike + '">%0</div> <input name="franshiz[' + hike + ']" class="form-control myinput" id="franshiz' + hike + '" onchange="calculate_sum()"> </td> <td> <input name="total[' + hike + ']" class="form-control" id="total' + hike + '" onchange="calculate_sum()" > <td><a class="btn btn-danger remove_field btn-xs" onclick="DeleteFields(' + hike + ')"><span class="glyphicon glyphicon-remove"></span>حذف</a></td></tr>');

        daroha.push({id : hike, selected : 0});
        hike++;
    });
    $("#send").click(function(e) {
        if(expire==1){
            e.preventDefault();
            alert('قرارداد این شخص به پایان رسیده است.');
            register=0;
            send_to_expert=0;
            ticket=0;
        }
        if(!document.getElementById("check").checked){
            if(ticket==0){
                register=0;
                send_to_expert=1;
            }else{
                register=0;
                send_to_expert=0;
            }
            alert('بیمه گر تایید نکرده است');
        }else{
            validate();
        }
        if(register==0){
            e.preventDefault();
        }
        if(send_to_expert==1){
            e.preventDefault();
            alert(' شما اجازه ثبت این نسخه را ندارید ...لطفا به کارشناس ارجاع دهید ');
        }
        if(ticket==1){
            e.preventDefault();
            alert(' شما اجازه ثبت این نسخه را ندارید ...لطفا یک تیکت دستی ایجاد کنید ');
        }
    });
    $("#expert").click(function(e) {
        if(expire==1){
            e.preventDefault();
            alert('قرارداد این شخص به پایان رسیده است.');
            register=0;
            send_to_expert=0;
            ticket=0;
        }
        if(send_to_expert==0){
            e.preventDefault();
        }
        if(register==1){
            e.preventDefault();
            alert(' شما اجازه ارسال به کارشناس را ندارید ...لطفا ثبت نسخه کنید ');
        }

        if(ticket==1){
            e.preventDefault();
            alert(' شما اجازه ارسال به کارشناس را ندارید ...لطفا یک تیکت دستی ایجاد کنید ');
        }
    });
    $("#ticket").click(function(e) {
        if(expire==1){
            e.preventDefault();
            alert('قرارداد این شخص به پایان رسیده است.');
            register=0;
            send_to_expert=0;
            ticket=0;
        }
        if(ticket==0){
            e.preventDefault();
            //alert('  ...لطفا یک تیکت دستی ایجاد کنید ');
        }
        if(send_to_expert==1){
            e.preventDefault();
            alert(' شما اجازه ایجاد تیکت دستی را ندارید ...لطفا به کارشناس ارجاع دهید ');
        }
        if(register==1){
            e.preventDefault();
            alert(' شما اجازه ایجاد تیکت دستی را ندارید ...لطفا ثبت نسخه کنید ');
        }

    });
});
function others(s){
    var other=0;
    daroha.forEach(function(a){
        if(a.selected==1 && parseFloat(document.getElementById("others"+s).value)>=0){
            other=parseFloat(document.getElementById("others"+s).value)+other;
        }
    });
    document.getElementById("presc_others").value=other;
}
function DeleteFields(id) {

    var obj = daroha.filter(function(o) {
        return o.id == id;
    });
    var index = daroha.indexOf(obj[0]);
    if (index != -1) {
        daroha.splice(index, 1);
    }
    document.getElementById('tr' + id).remove();
    calculate_sum();
    validate();
}
function myChange(s) {

    var med_price = document.getElementById("price" + s).value;
    var c = document.getElementById("count" + s).value;
    var a = document.getElementById("open_market" + s).value;
    if (a != 0) {
        var diff = (a - med_price) * document.getElementById("count" + s).value;
        document.getElementById("others" + s).value = diff;
    }

    document.getElementById("total" + s).value = med_price * c;
    document.getElementById("iran_percent" + s).value = document.getElementById("iran_percent" + s).value * c;
    document.getElementById("franshiz" + s).value = document.getElementById("franshiz" + s).value * c;
    document.getElementById("first_insure_percent" + s).value = document.getElementById("first_insure_percent" + s).value * c;
    calculate_sum();
}
function open_market(s) {
    var med_price = document.getElementById("price" + s).value;
    var a = document.getElementById("open_market" + s).value;


    var diff = (a - med_price) * document.getElementById("count" + s).value;
    document.getElementById("others" + s).value = diff;
    calculate_sum();

}
function down(){
    $('#result').html('');
    timer=setTimeout(function(){
        var insured=$('#insured').val();

        if(insured.length>0){
            $.post('../search',{insured: insured},function(data){
                if(data){
                    $('#result').html(data);
                }
                else {
                    $('#result').html('<span style="padding:5px" class="text-danger">چیزی یافت نشد</span>');
                }

            });
            $('#dropsearch').addClass("open");
        }
        else{
            $('#dropsearch').removeClass("open");
        }},250);
}
function find_med(id){
    $('#med_find_result'+id).html('');
    timer=setTimeout(function(){
        var medicine=$('#medicine'+id).val();

        if(medicine.length>0){
            $.post('../findmed',{medicine: medicine , id: id},function(data){
                if(data){
                    $('#med_find_result'+id).html(data);
                }
                else {
                    $('#med_find_result'+id).html('<span style="padding:5px" class="text-danger">چیزی یافت نشد</span>');
                }

            });
            $('#dropsearch'+id).addClass("open");
        }
        else{
            $('#dropsearch'+id).removeClass("open");
        }},500);
}
function find_doc(){
    $('#doctor-result').html('');
    timer=setTimeout(function(){
        var doctor=$('#doctor').val();

        if(doctor.length>0){
            $.post('../doctorearch',{doctor: doctor },function(data){
                if(data){
                    $('#doctor-result').html(data);
                }
                else {
                    $('#doctor-result').html('<span style="padding:5px" class="text-danger">چیزی یافت نشد</span>');
                }

            });
            $('#dropdoc').addClass("open");
        }
        else{
            $('#dropdoc').removeClass("open");
        }},500);
}
function selectDoc(id){
    $.get('../selectdoc/'+id,function(data){
        var doctor=data[0];
        $('#doctor_id').val(doctor['id']);
        $('#doctor').val(doctor['fname']+' '+doctor['lname']+'- کد نظام پزشکی :'+doctor['medical_code']);
        $('#expertise').html('تخصص پزشک : '+data[1]['name']);
    });
}
function selectMed(med_id,input_id) {
    var test=0;
    var med;
    var doc=$('#doctor_id').val();
    var insured_id=$('#insured_id').val();
    $('#medicine_spec'+input_id).html('');
    if(insured_id>0){
        $.get('../selectmed/'+med_id+'/'+doc+'/'+insured_id,function(data){
            if(data){
                if(data=='no'){
                    alert('این پزشک تخصص تجویز این دارو را ندارد.');
                    $('#medicine'+input_id).val('');
                }else{
                    $('#medicine'+input_id).val($('#med_r'+med_id).html());
                    med=data[0];
                    var specs=data[1];
                    if(specs!='no'){
                        specs.forEach(function(spec){
                            $('#medicine_spec'+input_id).append('<span class="label label-primary">'+spec['name']+'</span>');
                        });
                        $('#med_spec'+input_id).val(1);

                    }

                    var obj = daroha.filter(function(o) {
                        return o.id == input_id;
                    });
                    var index = daroha.indexOf(obj[0]);
                    if (index != -1) {
                        test=daroha[index]['selected'];
                        daroha[index]['selected'] = 1;
                    }
                    var m = {
                        price : med['price'],
                        first_percent : med['first_insure_percent'],
                        iran_percent : med['iran_percent']
                    };
                    document.getElementById("medicineid" + input_id).value=data[0]['id'];
                    /*if(test==1){
                     document.getElementById("presc_total").value =parseFloat(document.getElementById("presc_total").value)-parseFloat(document.getElementById("total"+input_id).value);
                     document.getElementById("presc_iran").value =parseFloat(document.getElementById("presc_iran").value)-parseFloat(document.getElementById("iran_percent"+input_id).value);
                     document.getElementById("presc_insured").value =parseFloat(document.getElementById("presc_insured").value)-parseFloat(document.getElementById("franshiz"+input_id).value);
                     document.getElementById("presc_first").value =parseFloat(document.getElementById("presc_first").value)-parseFloat(document.getElementById("first_insure_percent"+input_id).value) ;
                     document.getElementById("presc_others").value =0;
                     }*/
                    document.getElementById("price" + input_id).value = m['price'];
                    document.getElementById("count" + input_id).value = 1;
                    document.getElementById("unit" + input_id).value = 1;
                    document.getElementById("order_per_hour" + input_id).value = 8;
                    document.getElementById("open_market" + input_id).value = 0;
                    document.getElementById("others" + input_id).value = 0;
                    document.getElementById("fp" + input_id).innerHTML = '%' + m['first_percent'];
                    var first_insure_percent=m['price'] * m['first_percent'] / 100;
                    document.getElementById("first_insure_percent" + input_id).value = first_insure_percent;
                    var iran_percent=0;
                    var franshiz=0;
                    var rest = 100 - m['first_percent'];
                    var iran = 100 - contract_f;
                    if (m['iran_percent'] != 0) {
                        document.getElementById("ip" + input_id).innerHTML = '%' + m['iran_percent'];
                        iran_percent=m['price'] * m['iran_percent'] / 100;
                        document.getElementById("iran_percent" + input_id).value = iran_percent;
                        document.getElementById("pp" + input_id).innerHTML = '%' + (100 - m['iran_percent'] - m['first_percent']);
                        franshiz=(100 - m['iran_percent'] - m['first_percent']) * m['price'] / 100;
                        document.getElementById("franshiz" + input_id).value = franshiz;
                    } else {
                        document.getElementById("ip" + input_id).innerHTML = '%' + iran * rest / 100;
                        iran_percent=iran * rest / 100 * m['price'] / 100;
                        document.getElementById("iran_percent" + input_id).value = iran_percent;
                        document.getElementById("pp" + input_id).innerHTML = '%' + rest * contract_f / 100;
                        franshiz=rest * contract_f / 100 * m['price'] / 100;
                        document.getElementById("franshiz" + input_id).value = franshiz;
                    }
                    document.getElementById("total" + input_id).value = m['price'];
                    calculate_sum();
                    validate();
                }
            }
        });
    }else{
        alert('بیمار انتخاب نشده است.')
    }



}
function getspecs(med_id,doc,input_id){
    var med;
    var insured_id=$('#insured_id').val();
    $.get('../selectmed/'+med_id+'/'+doc+'/'+insured_id,function(data){
        if(data){
                med=data;
                var specs=data[1];
                if(specs!='no'){
                    specs.forEach(function(spec){
                        $('#medicine_spec'+input_id).append('<span class="label label-primary">'+spec['name']+'</span>');
                    });
                    $('#med_spec'+input_id).val(1);

                }}
});
}
function selectInsured(id) {

    register=1;
    send_to_expert=0;
    ticket=0;
    baghiha=[];
    permision = 1;

    $('#dropsearch').removeClass("open");

    $('#contarct_specs').html('');
    var insured;
    var insurer;
    var contract;
    var presc_commit;
    var insured_prsc_commit;
    var insured_prescs;
    $.get('../selectinsured/'+id,function(data){
        if(data){
            insured=data[0];
            insurer=data[1];
            contract=data[2];
            $('#insured_id').val(insured['id']);
            $('#insured').val(insured['lname']+' '+insured['fname']+' - کد ملی:'+insured['melli_code']);
            document.getElementById("insured_id").value=insured['id'];
            $('#company').html('شرکت :'+insurer['name']);
            $('#type').html('وابستگی :'+insured['type']);
            $('#sponser').html('وضعیت تکفل :'+insured['sponser_status']);
            document.getElementById("prescs").innerHTML = '';
            var ohde=contract['others'];
            document.getElementById('ohde').innerHTML=ohde;
            var today = new Date();
            var ntoday = today.toLocaleDateString().split('/');
            var nyear = ntoday[2];
            var nmonth = ntoday[0];
            var nday = ntoday[1];
            var finish=contract['gfinish_date'].split('-');
            if(nyear>parseInt(finish[0])){
                expire=1;
                alert('قرارداد این شخص به پایان رسیده است.');
            }
            if (nyear==parseInt(finish[0])){

                if(nmonth>parseInt(finish[1])){
                    expire=1;
                    alert('قرارداد این شخص به پایان رسیده است.');
                }
                if (nmonth==parseInt(finish[1])){
                    if (nday>parseInt(finish[2])){
                        expire=1;
                        alert('قرارداد این شخص به پایان رسیده است.');
                    }
                }
            }
            presc_commit=data[3]['value'];
            insured_prsc_commit = data[4];
            if (insured['type'] == 'main') {
                document.getElementById("type").innerHTML = 'وابستگی : اصلی';
                document.getElementById("sponser").innerHTML = 'وضعیت تکفل : -';
                contract_f = insured_prsc_commit['insured_f'];
            } else {

                document.getElementById("type").innerHTML = 'وابستگی : وابسته';
                document.getElementById("sponser").innerHTML = 'وضعیت تکفل : ' + insured['sponser_status'];
                if (insured['sponser_status'] == 'تحت تکفل') {
                    contract_f = insured_prsc_commit['depend_f'];
                } else {
                    contract_f = insured_prsc_commit['non_depend_f'];
                }
            }
            document.getElementById('meds1').innerHTML = '';

            insured_prescs = data[5];

            if (insured_prescs.length > 0  ) {
                var c = 1;
                var prescss;
                insured_prescs.forEach(function(s) {
                    prescss= prescss + '<tr><td>'+c+'</td><td><a href="../presc/'+ s.id+'" target="_blank"  style="margin:5px">نسخه شمار' + c + '</a></td><td>'+ s['reception_date']+'</td></tr>';
                    c++;
                });

                $('#prescs').html(prescss);

            }
            var insured_mdes=data[6];
            if (insured_mdes.length > 0  ) {
                insured_mdes.forEach(function (m) {
                    if(m['medicine_id']>0) {
                        var created_at=m.created_at.split(' ');
                        var date = new Date(created_at[0]);
                        var year = date.getFullYear();
                        var month = date.getMonth();
                        var day = date.getDay();

                        var difdays=0;
                        var order = (m.order_per_hour * m['count']*m['unit']) / 24;
                        var difdays=Math.floor((today.getTime()-date.getTime())/(1000*60*60*24));
                        var baghi = Math.round(((order - difdays) * 24) / m.order_per_hour);
                        if ((baghi > 0 && m['medicine_id'] > 0) || (m.order_per_hour==0 && m.id> 0)) {
                            if (baghiha.length==0) {
                                if(baghi== Infinity ){
                                    baghi=m['count']*m['unit'];
                                }

                                document.getElementById('meds1').innerHTML = '<table class="table table-bordered" id="meds" style="color: red;"> <tr class="danger"> <td>نام دارو</td> <td>تعداد</td> </tr> <tr><td style="direction: ltr;font-family: tahoma">' +m['medicine_name'] + '</td><td>' + baghi + '</td></tr></table>';

                            } else {
                                if(baghi== Infinity ){
                                    baghi=m['count']*m['unit'];
                                }

                                    document.getElementById('meds').innerHTML = document.getElementById('meds').innerHTML + '<tr><td style="direction: ltr;font-family: tahoma">' +m['medicine_name']  + '</td><td>' + baghi + '</td></tr>';


                            }
                            baghiha.push(m['medicine_id']);
                        }
                    }
                });
            }
        }
        var contarct_specs=data[7];
        if(contarct_specs.length>0) {
            $('#spec_en').val(1);
            contarct_specs.forEach(function (spec) {
                $('#contarct_specs').append('<label style="padding: 0px 5px">' + spec['name'] + ' - اعتبار باقیمانده :' + '<span class="' + spec['id'] + '">' + spec['value'] + '</span><input type="radio" name="insured_spec" value="' + spec['id'] + '" class="text-center hi" checked> </label>')
            });
        }


    });


}
function selectInsuredexp(id) {

    register=1;
    send_to_expert=0;
    ticket=0;
    baghiha=[];
    permision = 1;

    $('#dropsearch').removeClass("open");
    var insured;
    var insurer;
    var contract;
    var presc_commit;
    var insured_prsc_commit;
    var insured_prescs;
    $.get('../selectinsured/'+id,function(data){
        if(data){
            insured=data[0];
            insurer=data[1];
            contract=data[2];
            $('#insured_id').val(insured['id']);
            $('#insured').val(insured['lname']+' '+insured['fname']+' - کد ملی:'+insured['melli_code']);
            document.getElementById("insured_id").value=insured['id'];
            $('#company').html('شرکت :'+insurer['name']);
            $('#type').html('وابستگی :'+insured['type']);
            $('#sponser').html('وضعیت تکفل :'+insured['sponser_status']);
            document.getElementById("prescs").innerHTML = '';
            var ohde=contract['others'];
            document.getElementById('ohde').innerHTML=ohde;
            var today = new Date();
            var ntoday = today.toLocaleDateString().split('/');
            var nyear = ntoday[2];
            var nmonth = ntoday[0];
            var nday = ntoday[1];
            var finish=contract['gfinish_date'].split('-');
            if(nyear>parseInt(finish[0])){
                expire=1;
                alert('قرارداد این شخص به پایان رسیده است.');
            }
            if (nyear==parseInt(finish[0])){

                if(nmonth>parseInt(finish[1])){
                    expire=1;
                    alert('قرارداد این شخص به پایان رسیده است.');
                }
                if (nmonth==parseInt(finish[1])){
                    if (nday>parseInt(finish[2])){
                        expire=1;
                        alert('قرارداد این شخص به پایان رسیده است.');
                    }
                }
            }
            presc_commit=data[3]['value'];
            insured_prsc_commit = data[4];
            if (insured['type'] == 'main') {
                document.getElementById("type").innerHTML = 'وابستگی : اصلی';
                document.getElementById("sponser").innerHTML = 'وضعیت تکفل : -';
                contract_f = insured_prsc_commit['insured_f'];
            } else {

                document.getElementById("type").innerHTML = 'وابستگی : وابسته';
                document.getElementById("sponser").innerHTML = 'وضعیت تکفل : ' + insured['sponser_status'];
                if (insured['sponser_status'] == 'تحت تکفل') {
                    contract_f = insured_prsc_commit['depend_f'];
                } else {
                    contract_f = insured_prsc_commit['non_depend_f'];
                }
            }
            document.getElementById('meds1').innerHTML = '';

            insured_prescs = data[5];

            if (insured_prescs.length > 0  ) {
                var c = 1;
                var prescss;
                insured_prescs.forEach(function(s) {
                    prescss= prescss + '<tr><td>'+c+'</td><td><a href="../presc/'+ s.id+'" target="_blank"  style="margin:5px">نسخه شمار' + c + '</a></td><td>'+ s['reception_date']+'</td></tr>';
                    c++;
                });

                $('#prescs').html(prescss);

            }
            var insured_mdes=data[6];
            if (insured_mdes.length > 0  ) {
                insured_mdes.forEach(function (m) {
                    if(m['medicine_id']>0) {
                        var created_at=m.created_at.split(' ');
                        var date = new Date(created_at[0]);
                        var year = date.getFullYear();
                        var month = date.getMonth();
                        var day = date.getDay();

                        var difdays=0;
                        var order = (m.order_per_hour * m['count']*m['unit']) / 24;
                        var difdays=Math.floor((today.getTime()-date.getTime())/(1000*60*60*24));
                        var baghi = Math.round(((order - difdays) * 24) / m.order_per_hour);
                        if ((baghi > 0 && m['medicine_id'] > 0) || (m.order_per_hour==0 && m.id> 0)) {
                            if (baghiha.length==0) {
                                if(baghi== Infinity ){
                                    baghi=m['count']*m['unit'];
                                }

                                document.getElementById('meds1').innerHTML = '<table class="table table-bordered" id="meds" style="color: red;"> <tr class="danger"> <td>نام دارو</td> <td>تعداد</td> </tr> <tr><td style="direction: ltr;font-family: tahoma">' +m['medicine_name'] + '</td><td>' + baghi + '</td></tr></table>';

                            } else {
                                if(baghi== Infinity ){
                                    baghi=m['count']*m['unit'];
                                }

                                    document.getElementById('meds').innerHTML = document.getElementById('meds').innerHTML + '<tr><td style="direction: ltr;font-family: tahoma">' +m['medicine_name']  + '</td><td>' + baghi + '</td></tr>';


                            }
                            baghiha.push(m['medicine_id']);
                        }
                    }
                });
            }
        }


       /* contarct_specs.forEach(function(spec){
            $('#contarct_specs').append('<label style="padding: 0px 5px">'+spec['name']+' - اعتبار باقیمانده :'+'<span class="'+spec['id']+'">'+spec['value']+'</span><input type="radio" name="insured_spec" value="'+spec['id']+'" class="text-center hi" checked> </label>')
        });*/


    });


}

function validate(){
    register=1;
    send_to_expert=0;
    ticket=0;

    var total=0;
    var index=-1;
    var times=3;
    var repeated=0;
    daroha.forEach(function(a){
        if(repeated<times){
            repeated=0
        }
        if(a.selected==1 && parseFloat(document.getElementById("total"+a.id).value)>=0){
            total=parseFloat(document.getElementById("total"+a.id).value)+total;
            index=baghiha.indexOf(parseInt(document.getElementById("medicineid"+a.id).value));

            if(index>=0){
                register=0;
                send_to_expert=0;
                ticket=1;
                permision=0;
                alert('عدم امکان ارایه دارو بعلت تعداد باقی مانده موجودی داروی بیمار') ;

            }

            $.get('../selcetmedicine/'+parseInt(document.getElementById("medicineid"+a.id).value),function(data){
                if(data){
                    var kh=data;
                    if(kh['type']==2){
                        if(ticket==0){
                            register=0;
                            send_to_expert=1;
                        }else{
                            register=0;
                            send_to_expert=0;
                        }
                        permision=0;
                        alert('داروهای درخواستی در لیست اقلام خارجی میباشند.');
                    }
                    inprescs.forEach(function(h){
                        var inmeds= prescMed.filter(function(l){
                            return l['pres_id']== h.id;
                        });

                        inmeds.forEach(function(b){
                            if(parseInt(document.getElementById("medicine"+a.id).value)== b['medicine_id']){
                                if(repeated<times){
                                    if(h.status==1){
                                        repeated++;
                                    }
                                }
                            }
                        });
                    });
                }
            });
        }
    });
    if(repeated==times){
        if(ticket==0){
            register=0;
            send_to_expert=1;
        }else{
            register=0;
            send_to_expert=0;
        }
        permision=0;
        alert('عدم امکان ارایه دارو بعلت گذشتن از دفعات مجاز ارایه بدون تایید کارشناس');
    }
    if(total>parseFloat(setting[0]['value'])){
        if(ticket==0){
            register=0;
            send_to_expert=1;
        }else{
            register=0;
            send_to_expert=0;
        }
        permision=0;
        alert('مبلغ از حد مجاز بیشتر میباشد');
    }
}
function calculate_sum(){
    var presc_total=0;
    var presc_iran=0;
    var presc_insured=0;
    var presc_first=0;
    var presc_others=0;
    var specs=0;
    var payable=0;
    var left_oevr=0;
    var total_insured=0;
    daroha.forEach(function(a){
        if(parseFloat(document.getElementById("total"+a.id).value)>0){
            presc_total=parseFloat(document.getElementById("total"+ a.id).value)+presc_total;
        }
        if(parseFloat(document.getElementById("iran_percent"+a.id).value)>0){
            presc_iran=parseFloat(document.getElementById("iran_percent"+ a.id).value)+presc_iran;
        }
        if(parseFloat(document.getElementById("franshiz"+a.id).value)>0){
            presc_insured=parseFloat(document.getElementById("franshiz"+ a.id).value)+presc_insured;
        }
        if(parseFloat(document.getElementById("first_insure_percent"+a.id).value)>0){
            presc_first=parseFloat(document.getElementById("first_insure_percent"+ a.id).value)+presc_first;
        }
        if(parseFloat(document.getElementById("others"+a.id).value)>0){
            presc_others=parseFloat(document.getElementById("others"+ a.id).value)+presc_others;
        }
        if(parseInt(document.getElementById("med_spec"+a.id).value)>0 && parseFloat(document.getElementById("franshiz"+a.id).value)>0){
            specs=parseFloat(document.getElementById("franshiz"+a.id).value)+specs;
        }
    });
    document.getElementById("presc_total").value =presc_total;
    document.getElementById("presc_iran").value =presc_iran;
    document.getElementById("presc_insured").value =presc_insured;
    document.getElementById("presc_first").value =presc_first;
    document.getElementById("presc_others").value=presc_others;
    document.getElementById("total_specs").value=specs;
    var selected_spec=$( ".hi:checked" ).val();
    if(selected_spec>0){
        var spec_hi=parseFloat($('.'+selected_spec).html());
        if(spec_hi>=specs){
            payable=specs;
            left_oevr=0;
        } else {
            payable=spec_hi;
            left_oevr=specs-spec_hi;
        }

    } else{
        payable=0;
        left_oevr=specs;
    }
    if(left_oevr>0){
        $('#left_over').addClass('text-danger');
    }else{
        $('#left_over').removeClass('text-danger');
    }
    document.getElementById("payable").value=payable;
    document.getElementById("left_over").value=left_oevr;
    total_insured=presc_insured-payable;
    document.getElementById("total_insured").value=total_insured;

}

/**
 * Created by Navid on 4/25/2016.
 */
