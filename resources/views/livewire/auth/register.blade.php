<x-card title="Criação de conta" shadow class="mx-auto w-[500px]">

    <x-form wire:submit="submit" class="mt-4">
        <x-input label="Nome" wire:model="name"/>
        <x-input label="Email" wire:model="email"/>
        <x-input label="Confirmação de email" wire:model="email_confirmation"/>
        <x-input label="Senha" wire:model="password" type="password"/>
        <x-input label="Confirmação de senha" wire:model="password_confirmation" type="password"/>
        <div class="w-full flex items-center justify-between">
            <x-slot:actions>
                <div class="w-full flex items-center justify-between">
                    <div>
                        <a wire:navigate href="{{ route('login') }}"
                           class="link link-primary"> Login </a>
                        <br/>
                        <a wire:navigate href="{{ route('password.recovery') }}"
                           class="link link-primary"> Recuperar a senha </a>
                    </div>
                    <div class="space-x-3">
                        <x-button label="Registar" class="btn-primary" type="submit" spinner="save"/>
                    </div>
                </div>
            </x-slot:actions>
        </div>
    </x-form>


</x-card>




