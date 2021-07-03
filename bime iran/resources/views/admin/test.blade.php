@extends('master')
@section('content')

<link rel="stylesheet" href="{{url('assets/css/formValidation.min.css')}}">
<script src="{{url('assets/js/formValidation.min.js')}}"></script>
<script src="{{url('assets/js/fr/bootstrap.min.js')}}"></script>
<script src="{{url('assets/js/fa_IR.js')}}"></script>
<form id="productForm">
    <div class="form-group col-sm-12">
        <label>Product name</label>
        <input type="text" class="form-control" name="name" />
    </div>

    <div class="form-group col-sm-12">
        <label>Price</label>
        <div class="input-group">
            <div class="input-group-addon">$</div>
            <input type="text" class="form-control" name="price" />
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label>Size</label>
        <div class="checkbox">
            <label><input type="checkbox" name="size[]" value="s" /> S</label>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="size[]" value="m" /> M</label>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="size[]" value="l" /> L</label>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="size[]" value="xl" /> XL</label>
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label>Available in store</label>
        <div class="radio">
            <label><input type="radio" name="availability" value="yes" /> Yes</label>
        </div>
        <div class="radio">
            <label><input type="radio" name="availability" value="no" /> No</label>
        </div>
    </div>

    <!-- Do NOT use name="submit" or id="submit" for the Submit button -->
    <button type="submit" class="btn btn-default col-sm-12">Add product</button>
</form>

<script>
    $(document).ready(function() {
        $('#productForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            locale: 'fa_IR',
            fields: {
                name: {
                    validators: {
                        notEmpty: {

                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                           },
                        regexp: {
                            regexp: /^[b-zA-Z0-9\s\()+_\.]+$/
                             }
                    }
                },
                price: {
                    validators: {
                        notEmpty: {

                        },
                        numeric: {

                        }
                    }
                },
                'size[]': {
                    validators: {
                        notEmpty: {

                        }
                    }
                },
                availability: {
                    validators: {
                        notEmpty: {

                        }
                    }
                }
            }
        });
    });
</script>
@endsection