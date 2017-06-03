@extends('admin.general.edit-add')
@section('relationFields')
    @include('admin.templates.select_option',
    [
    'name'         => "menu_id",
    'options'      => App\Menus::all(),
    'label'        => "Основное меню",
    'key'          => "id",
    'display_name' => "name",
    ])

    @include('admin.templates.select_option',
    [
    'name'         => "parent_id",
    'options'      => App\MenuItem::all(),
    'label'        => "Связанный пункт меню",
    'key'          => "id",
    'display_name' => "title",
    ])

    <div class="form-group {{ $errors->has('target') ? ' has-error' : '' }}">
        <label>Основное меню</label>
        <select name="target" class="form-control">
            <?php $targets =
                ['_self'  => 'Загружать страницу в текущее окно' ,
                '_blank' => 'Загружать страницу в новое окно браузера'];
            ?>
            @foreach($targets as $key => $target)
                <option
                        value="{{$key}}"
                        <?php
                        if(old('target') == $key){
                            echo "selected";
                        }elseif(isset($dataTypeContent) && $dataTypeContent->target == $key){
                            echo "selected";
                        }
                        ?>
                >
                    {{$target}}
                </option>
            @endforeach
        </select>
        @if ($errors->has('target'))
            <span class="help-block">
                <strong>{{ $errors->first('target') }}</strong>
            </span>
        @endif
    </div>

@stop




