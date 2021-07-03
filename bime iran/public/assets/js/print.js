/*var today = new Date();
var permision=1;
function company() {
    var pres = prescs.filter(function(o) {
        return o.insured_id == insured;
    });
    var c = 1;
    if (pres.length > 0) {
        pres.forEach(function(s) {

            var meds1 = prescMed.filter(function(a) {
                return a.pres_id == s.id;
            });
            meds1.forEach(function(m) {
                var name = medicines.filter(function(n) {
                    return n.id == m.medicine_id;
                });
                var created_at=m.created_at.split(' ');
                var date = new Date(created_at[0]);
                var year = date.getFullYear();
                var month = date.getMonth();
                var day = date.getDay();
                var difdays=0;
                var order = (m.order_per_hour * m['count']*m['unit']) / 24;
                var difdays=Math.floor((today.getTime()-date.getTime())/(1000*60*60*24));
                var baghi = Math.round(((order - difdays) * 24) / m.order_per_hour);
                if ((baghi > 0 && name.length > 0) || (m.order_per_hour==0 && name.length > 0)) {
                    if (permision == 1) {
                        if(baghi== Infinity ){
                            baghi=m['count']*m['unit'];
                        }
                        document.getElementById('meds1').innerHTML = '<table class="table table-bordered" id="meds" style="color: red;"> <tr class="danger"> <td>نام دارو</td> <td>تعداد</td> </tr> <tr><td style="direction: ltr;font-family: tahoma">' + name[0]['name'] + '</td><td>' + baghi + '</td></tr></table>';
                    } else {
                        if(baghi== Infinity ){
                            baghi=m['count']*m['unit'];
                        }
                        permision=0;
                        document.getElementById('meds').innerHTML = document.getElementById('meds').innerHTML + '<tr><td style="direction: ltr;font-family: tahoma">' + name[0]['name'] + '</td><td>' + baghi + '</td></tr>';

                    }
                }
            });
            c++;
        });
    }
}*/
var today = new Date();
var permision=1;
var baghiha=[];
function company() {




    var i=insured;

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
    document.getElementById('meds1').innerHTML = '';
    var pres = prescs.filter(function(o) {
        return o.insured_id == insured;
    });
    var c = 1;

    if (pres.length > 0  ) {
        inprescs=pres;
        pres.forEach(function(s) {

            var meds1 = prescMed.filter(function(a) {
                return a.pres_id == s.id;
            });
            meds1.forEach(function(m) {
                var name = medicines.filter(function(n) {
                    return n.id == m.medicine_id;
                });
                var created_at=m.created_at.split(' ');
                var date = new Date(created_at[0]);
                var year = date.getFullYear();
                var month = date.getMonth();
                var day = date.getDay();

                var difdays=0;
                var order = (m.order_per_hour * m['count']*m['unit']) / 24;
                var difdays=Math.floor((today.getTime()-date.getTime())/(1000*60*60*24));
                var baghi = Math.round(((order - difdays) * 24) / m.order_per_hour);
                if ((baghi > 0 && name.length > 0) || (m.order_per_hour==0 && name.length > 0)) {
                    if (baghiha.length==0) {
                        if(baghi== Infinity ){
                            baghi=m['count']*m['unit'];
                        }
                        document.getElementById('meds1').innerHTML = '<table class="table table-bordered" id="meds" style="color: red;"> <tr class="danger"> <td>نام دارو</td> <td>تعداد</td> </tr> <tr><td style="direction: ltr;font-family: tahoma">' + name[0]['name'] + '</td><td>' + baghi + '</td></tr></table>';
                    } else {
                        if(baghi== Infinity ){
                            baghi=m['count']*m['unit'];
                        }
                        document.getElementById('meds').innerHTML = document.getElementById('meds').innerHTML + '<tr><td style="direction: ltr;font-family: tahoma">' + name[0]['name'] + '</td><td>' + baghi + '</td></tr>';
                    }
                    baghiha.push(name[0]['id']);

                }
            });
            c++;
        });
    }



}
