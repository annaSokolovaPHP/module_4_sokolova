@extends('admin.general.edit-add')
@section('relationFields')

    @include('admin.templates.select_option',
    [
    'name'         => "data_type_id",
    'options'      => App\DataType::all(),
    'label'        => "Модель",
    'key'          => "id",
    'display_name' => "display_name_singular",
    ])



    <div class="form-group">
        <label>Поле модели</label>

        <?php $models = App\DataType::all()?>

        <select name="field" class="form-control {{ $errors->has('field') ? ' has-error' : '' }}">
            @foreach($models as $model)
                <optgroup label="{{$model->display_name_singular}}" id = "{{$model->id}}">
                        <?php
                        $model_name = "App\\".$model->model_name;
                        $model = new $model_name;
                        $modelFillableFields = $model->getFillable();
                        ?>
                    @foreach($modelFillableFields as $field)
                        <option
                                value="{{$field}}"
                        <?php
                            if(old('field') == $field){
                                echo "selected";
                            }elseif(isset($dataTypeContent) && $dataTypeContent->field== $field){
                                echo "selected";
                            }
                            ?>
                        >
                            {{$field}}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @if ($errors->has('field'))
            <span class="help-block">
                <strong>{{ $errors->first('field') }}</strong>
            </span>
        @endif
    </div>

    <?php
    $model        = new App\DataRow;
    $options      = $model->getEnum();
    ?>
    @include('admin.templates.select_option',
    [
    'name'         => "type",
    'options'      => $options,
    'label'        => "Тип поля",
    ])

@stop

@section('script')

    <script>
        select = $("[name='field']");
        // загоняем все <optgroup> в массив
        var allOpts = select.children();

        $("[name='data_type_id']").change(function (){
            var modelValue = $(this).val();
            allOpts.detach().each(function(){
                var val = $(this).attr('id');
                if (val == modelValue)
                    $(this).appendTo(select);
            });

        });

        $("[name='field']").change(function (){
            var label = $(this.options[this.selectedIndex]).closest('optgroup').prop('id');
            $("[name='data_type_id']").val(label);
        });

    </script>

@stop
