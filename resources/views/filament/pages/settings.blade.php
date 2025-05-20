<x-filament-panels::page>
1. {{($this->user->roles)}}
<hr>
2. {{$this->team->id}}
<hr>
3. {{$this->role->permissions->contains('name', 'view-purchase')}}
<hr>
4. {{($this->role->name)}} <br>
{{($this->user->roles->first()->name)}}
<hr>
5. <pre>{{json_encode($this->role->permissions, JSON_PRETTY_PRINT)}}</pre>
<hr>
6. {{($this->permission->name)}}
<hr>
@if ($this->user->can('view-purchase', $this->team))
    <div class="text-2xl font-bold text-center">
        {{ __('You have permission to view purchase') }}
    </div>
@else
    <div class="text-2xl font-bold text-center">
        {{ __('You do not have permission to view purchase') }}
    </div>  
@endif
</x-filament-panels::page>
