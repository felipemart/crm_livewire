<x-card title="Login" shadow class="mx-auto w-[500px]">
    @if(session()->has('status'))
        <x-alert icon="o-exclamation-triangle" class="alert-warning">
            <span>{{ session()->get('status') }}</span>
        </x-alert>
    @endif

    @if($errors->hasAny(['invalidCredentials', 'rateLimiter']))
        <x-alert icon="o-exclamation-triangle" class="alert-warning">

            @error('invalidCredentials')
            <span>{{ $message }}</span>
            @enderror
            @error('rateLimiter')
            <span>{{ $message }}</span>
            @enderror

        </x-alert>
    @endif

    <x-form wire:submit="tryLogin" class="mt-4">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Senha" wire:model="password" type="password"/>

        <div class="w-full flex items-center justify-between">
            <x-slot:actions>
                <div class="w-full flex items-center justify-between">
                    <div>
                        <a wire:navigate href="{{ route('register') }}"
                           class="link link-primary"> Criar uma conta </a>
                        <br/>
                        <a wire:navigate href="{{ route('password.recovery') }}"
                           class="link link-primary"> Recuperar a senha </a>
                    </div>
                    <div class="space-x-3">
                        <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
                    </div>
                </div>
            </x-slot:actions>
        </div>
    </x-form>
</x-card>
