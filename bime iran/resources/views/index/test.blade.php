@extends('master')
@section('content')
    <?php
    $test=array();
    foreach($insurers as $insurer){
        $test1=array($insurer->id=>[$insurer->name,$insurer->website]);
        $test=$test+$test1;
    }
    ?>
    <script type="text/javascript">

        var test = <?php echo json_encode($test, JSON_PRETTY_PRINT) ?>;
        $(document).ready(function() {
            var max_fields      = 100; //maximum input boxes allowed
            var wrapper         = $(".tt"); //Fields wrapper
            var add_button      = $(".add_row"); //Add button ID

            var x = 1;
            $(add_button).click(function(e){
                e.preventDefault();
                    x++;
                $(wrapper).append( '<tr><td class="col-sm-2"><select name="insurer_id['+x+']" class="form-control" id="hi'+x+'" onchange="myFunction('+x+')"><?php echo '<option></option>'; foreach($insurers as $insurer){ echo '<option value="'.$insurer->id.'">'.$insurer->name.'</option>'; } ?> </select></td> <td><input type="text" name="address['+x+']" id="address'+x+'"></td> <td><input type="text" name="website['+x+']" id="website'+x+'"></td><td><button type="submit" class="btn btn-danger remove_field">حذف </button></td> </tr>');
                $('select').select2({
                    placeholder:'دارو',
                    dir: "rtl"
                });
                    alert(x);//add input box
            });

            $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parents('tr').remove();

            })
        });
        function myFunction(s) {
            document.getElementById("address"+s).value=test[document.getElementById("hi"+s).value][0];
            document.getElementById("website"+s).value=test[document.getElementById("hi"+s).value][1];
        }
    </script>
   {!! Form::open() !!}
    <div class="col-sm-8 col-sm-offset-2">
    <table class="col-sm-12 tt">
        <tr>
            <td>نام سازمان</td>
            <td>آدرس </td>
            <td>سایت</td>
            <td>حذف</td>
        </tr>
        <tr>
            <td class="col-sm-5"> <select name="insurer_id[1]" class="form-control " id="hi1" onchange="myFunction(1)">
                    <option></option>
                    @foreach($insurers as $insurer)
                        <option value='{{$insurer->id}}'>{{$insurer->name}}</option>
                        @endforeach
                </select>
            </td>
            <td><input type="text" name="address[1]" id="address1"></td>
            <td><input type="text" name="website[1]" id="website1"></td>
            <td></td>
        </tr>


    </table>

        <button class="add_row btn btn-info" style="float: right;margin-top: 5px">فیلد اضافه</button>
    </div>




    <div class="form-group col-sm-12" style="margin: auto;text-align: center">
        <button type="submit" class="btn btn-primary">
           ثبت نسخه
        </button>
    </div>



    {!! Form::close() !!}

@stop
@section('footer')
    <script>

        $('select').select2({
            placeholder:'دارو',
            dir: "rtl"
        });
    </script>
    @stop
