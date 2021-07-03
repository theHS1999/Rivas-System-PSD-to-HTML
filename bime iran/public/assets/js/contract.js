$(document).ready(function() {
var hike=0;
    var wrapper = $("#expert"); //Fields wrapper
    $(".add_row").click(function (e) {
        hike++;
        $(wrapper).append('<tr id="tr'+ hike +'"><td class="col-sm-6"><input name="count['+ hike +']" type="text" value="'+hike+'" hidden><select name="expert['+hike+']" id="expert'+hike+'" class="form-control select1">  </select></td><td><input value="0" name="seil['+hike+']" class="form-control"></td><td><a class="btn btn-danger delete_row">حذف</a></td></tr>');
        var test;
        specials.forEach(function(m) {
            test = test + '<option value="' + m.id + '">' + m.name + '</option>';
        });
        $('#expert' + hike).append(test);
        $('#expert' + hike).select2({
            placeholder : 'دارو',
        });
        $("#ee").val(hike);


    });
    $(wrapper).on('click','.delete_row',function(a){
        $(this).parents('tr').remove();
        hike--;
        $("#ee").val(hike);

    });

});
function franshiz(id){
var control=document.getElementById('control'+id).value;
    if(control=='دارد'){
        $('#franshiz'+id).slideToggle("slow");
    }
    if(control=='ندارد'){
        $('#franshiz'+id).slideToggle("slow");
    }

}
function requirecheck(id){

}