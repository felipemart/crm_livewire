<x-card title="Login" shadow class="mx-auto w-[400px]">

    <x-form wire:submit="submit" no-separator>
        <x-input label="Name" wire:model="name"/>
        <x-input label="Email" wire:model="email"/>
        <x-input label="Email Confirmation" wire:model="email_confirmation"/>
        <x-input label="Password" wire:model="password" type="password"/>
        <x-input label="Password Confirmation" wire:model="password_confirmation" type="password"/>

        <x-slot:actions>
            <x-button label="Cancel"/>
            <x-button label="Rigstrar" class="btn-primary" type="submit" spinner="save"/>
        </x-slot:actions>
    </x-form>


</x-card>




