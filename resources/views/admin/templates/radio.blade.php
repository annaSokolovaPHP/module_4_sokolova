<div class="form-group {{ $errors->has($name) ? ' has-error' : '' }}">
    @foreach($values as $value)
<div class="radio-inline ">
    <label><input type="radio" name="{{$name}}" value ="{{$value}}"
        <?php
            if(old($name) == $value){
                echo "checked";
            }elseif(isset($dataTypeContent) && $dataTypeContent->$name == $value){
                echo "checked";
            }
            ?>
        >
        {{$value}}
    </label>
    @if ($errors->has($name))
        <span class="help-block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
    @endforeach
</div>