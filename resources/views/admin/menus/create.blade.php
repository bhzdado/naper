@php
		$todosMenus[null] = 'Raiz';
		foreach ($allMenus as $menu):
			$pai = \App\Menu::where('id',$menu->parent_id)->get(['name'])->first();
			$todosMenus[$menu->id] = $menu->name.($menu->isChild() ? " - Submenu de ".$pai->name : "");
		endforeach
	@endphp
<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>
	<div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
	    <h5>
	        Administração de Menu
	    </h5>
	</div>
	<div class="widget-content nopadding">
		<div class="control-group">
					<div class="card-header">Inserção de conteúdo</div>
					<div class="card-body text-success">
						<h5 class="card-title">Inserir novo menu/submenu</h5>
						<p class="card-text">Escolha onde deseja cadastrar um novo menu e insira um item.</p>
					</div>
		</div>
		  <form class="form-horizontal" name="form_menu_create" id="form_menu_create">
				{{ csrf_field() }}
				<div class="control-group">
					{!! Form::label('name','Nome do Menu:') !!}
					{!! Form::text('name',(old('name') ?? null),['id' => 'name' ,'class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required']) !!}
					@if ($errors->has('name'))
						<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('name') }}</strong>
				</span>
					@endif
				</div>
				<div class="control-group">
					{!! Form::label('parent_id', 'Selecione onde irá inserir:') !!}
					{!! Form::select('parent_id', $todosMenus, (!isset($parent_id->id) ? null : $parent_id->id),['class' => 'form-control']); !!}
					{!! Form::label('published', 'Publicar?') !!}
					<label>
                {!! Form::radio('published', 0, true) !!}
                Não
            </label>
            <label>
                {!! Form::radio('published', 1, false) !!}
                sim
            </label>
				</div>
				<div class="form-actions">
            	<input type="button" value="Salvar" class="btn btn-success btn-save-form">
				</div>
		</form>
	</div>

<script src="{{asset('js/menu/script.js')}}"></script>
