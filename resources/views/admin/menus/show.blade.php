
		<table class="table table-bordered">
			<tbody>
			<tr>
				<th scope="row">ID</th>
				<td>{{ $menu->id }}</td>
			</tr>
			<tr>
				<th scope="row">Nome</th>
				<td><strong>{{ $menu->name }}</strong></td>
			</tr>
			<tr>
				<th scope="row">Filho/Submenu de:</th>
				<td>{{ $menu->father()->nome ?? 'Raiz' }}</td>
			</tr>
			<tr>
				<th scope="row">Menu Pai de (hierarquia):</th>
				<td>
				@if($menu->isFather())
					<ul>
					@foreach($menu->menus()->get() as $filho)
						<li>
							{{ $filho->nome }}
							@if($filho->isFather())
								@include('menus.partials.submenusimples', ['filhos' => $filho->menus])
							@endif
						</li>
					@endforeach
					</ul>
				@else
					Ninguém
				@endif
				</td>
			</tr>
			<tr>
				<th scope="row">Publicado?</th>
				<td>{{ ($menu->published == 1) ? 'Sim' : 'Não' }}</td>
			</tr>
			</tbody>
		</table>
		<div class="form-actions">
				<input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('menu');">
		</div>
