<div>
    <x-header title="Usuarios" separator progress-indicator>

        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"
                      icon="o-funnel" tooltip-bottom="Filtros"/>
            <x-button icon="o-plus" class="btn-primary" wire:navigate href="#"
                      tooltip-bottom="Cadastrar"/>
        </x-slot:actions>
    </x-header>
    <x-table :headers="$this->headers" :rows="$this->users" class="w-11/12">
        @scope('cell_permissions', $user)
        @foreach($user->permissions as $permission)
            <x-badge value="{{ $permission->key }}" class="badge-primary"/>
        @endforeach

        @endscope

        @scope('actions', $user)
        <span class="flex">
                    <x-button icon="o-pencil-square" wire:navigate
                              href="#" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>

                           <x-button
                               id="show-btn-{{ $user->id }}"
                               wire:key="show-btn-{{ $user->id }}"
                               icon="o-document-magnifying-glass"
                               wire:click="show('{{ $user->id }}')"
                               spinner
                               class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"
                           />
                     @unless(false)
                @unless($user->is(auth()->user()))
                    <x-button
                        id="delete-btn-{{ $user->id }}"
                        wire:key="delete-btn-{{ $user->id }}"
                        icon="o-trash"
                        wire:click="destroy('{{ $user->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-red-500" tooltip="Apagar"
                    />
                @endif
            @else
                <x-button
                    id="restore-btn-{{ $user->id }}"
                    wire:key="restore-btn-{{ $user->id }}"
                    icon="o-arrow-path-rounded-square"
                    wire:click="restore('{{ $user->id }}')"
                    spinner
                    class="btn-ghost btn-sm text-white-500" tooltip="Reativar"
                />
            @endunless
                    </span>
        @endscope
    </x-table>

</div>
