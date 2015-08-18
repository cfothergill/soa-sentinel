{{ if($required) }}
<div class="required-field-block">
{{ endif }}

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }}</label>
	<div class="datepicker form-group input-group">
		<input data-date-format="{{ $pickerFormat }}" data-date-useseconds="{{ $seconds ? 'true' : 'false' }}" class="form-control" name="{{ $name }}" type="text" id="{{ $name }}" value="{{ $value }}">
		<span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
	</div>
	@include(AdminTemplate::view('formitem.errors'))
	{{ if($required) }}
		@include(AdminTemplate::view('formitem.required'))
    {{ endif }}	
</div>

{{ if($required) }}
</div>
{{ endif }}