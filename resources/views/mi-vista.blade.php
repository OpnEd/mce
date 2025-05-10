
<h1>Hola Mundo</h1>
<p>Bienvenido a mi vista</p>

@if ($team)
    <p>El valor en la sesión es: {{ $team }}</p>
@endif

{{$user->team_id}}
@if ($user->can('view-batch'))
    <p>Puede ver batches</p>
@endif

@if ($user->can('delete-batch'))
    <p>Puede ver arbustos</p>
@endif

@if ($user->can('create-batch'))
    <p>Puede crear batches</p>
@endif

@if ($user->can('view-sale-item'))
    <p>Puede ver ventas</p>
@endif
@if ($user->can('create-sale-item'))
    <p>Puede crear ventas</p>
@endif

{{ $userRoles }}

<h1>{{ $user->name }}</h1>

@if ($user->can('create-batch'))
    <p>Puede crear batches</p>
@endif
