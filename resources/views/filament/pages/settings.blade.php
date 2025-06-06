<x-filament-panels::page>
1. {{($this->user->roles)}}
<hr>
2. {{$this->team->id}}
<hr>
3. {{$this->role->permissions->contains('name', 'confirm-purchase')}}
<hr>
4. {{($this->role->name)}} <br>
{{($this->user->roles->first()->name)}}
<hr>
5. <pre>{{json_encode($this->role->permissions, JSON_PRETTY_PRINT)}}</pre>
<hr>
6. {{($this->permission->name)}}
{{$this->user->roles->first()->permissions->contains('name', 'confirm-purchase') ? 'Yes' : 'No'}}
<hr>
@can('confirm', $this->team)
    <div class="text-2xl font-bold text-center">
        {{ __('You have permission to view purchase') }}
    </div>
@else
    <div class="text-2xl font-bold text-center">
        {{ __('You do not have permission to view purchase') }}
    </div>  
@endcan
</x-filament-panels::page>
