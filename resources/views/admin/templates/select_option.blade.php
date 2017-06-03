<div class="form-group {{ $errors->has($name) ? ' has-error' : '' }}">
    <label>{{$label}}</label>
    <select name="{{$name}}" class="form-control">
        @foreach($options as $option)
            @if(isset($key) && !empty($key)){
            {{$value = $option->$key}}
            @else{
            {{$value = $option}}
            @endif

            <option
                    value="{{$value}}"
            <?php
                if(old($name) == $value){
                    echo "selected";
                }elseif(isset($dataTypeContent) && $dataTypeContent->$name == $value){
                    echo "selected";
                }
                ?>
            >
                @if(isset($display_name) && !empty($display_name))
                    {{$option->$display_name}}
                @else
                    {{$option}}
                @endif
            </option>
        @endforeach
    </select>
    @if ($errors->has($name))
        <span class="help-block">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>