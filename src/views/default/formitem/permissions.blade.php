<table class="table table-striped table-hover">
	
	<thead>
		<tr>

			<th>
				Permission
			</th>

			<th>
				Allowed
			</th>

			<th>
				Denied
			</th>

			@if( $withInherited )
			<th>
				Inherited
			</th>
			@endif

		</tr>
	</thead>

	<tbody>
		
		@foreach ($all_permissions as $all_permission)
		
		<tr>
			
			<td>
				<a href="#" data-toggle="tooltip" title="{{ $all_permission['value']}}">{{ (!empty($all_permission['description'])) ? $all_permission['description'] : $all_permission['value'] }}</a>
			</td>


			<td>

				<input type="radio" name="{{ $fieldname }}[{{ $all_permission['value'] }}]" value="1" 
						@if( isset( $permissions[$all_permission['value']] ) ) 
							@if( $permissions[$all_permission['value']] == true)
								checked="checked"
							@endif
						@endif>
			</td>

			<td>
				<input type="radio" name="{{ $fieldname }}[{{ $all_permission['value'] }}]" value="0" 

					@if( isset( $permissions[$all_permission['value']] ) ) 
						@if( $permissions[$all_permission['value']] == false)
							checked="checked"
						@endif
					@endif>
			</td>

			@if( $withInherited )
			<td>
				<input type="radio" name="{{ $fieldname }}[{{ $all_permission['value'] }}]" value="2" @if( !isset( $permissions[$all_permission['value']] ) )checked="checked"@endif>
			</td>
			@endif

		</tr>
		@endforeach


	</tbody>


</table>