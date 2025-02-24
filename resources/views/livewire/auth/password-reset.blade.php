<x-card title="Recuperar Senha" shadow class="mx-auto w-[500px]">
    @if(session()->has('status'))
        <x-alert icon="o-exclamation-triangle" class="alert-warning">
            <span>{{ session()->get('status') }}</span>
        </x-alert>
    @endif
    <x-form wire:submit="resetPassword">
        <x-input label="Email" value="{{ $this->obfuscatedEmail }}" readonly/>
        <x-input label="Email Confirmation" wire:model="email_confirmation"/>
        <x-input label="Senha" wire:model="password" type="password"/>
        <x-input label="Confirmação de senha" wire:model="password_confirmation" type="password"/>

        <div class="w-full flex items-center justify-between">

            <x-slot:actions>
                <div class="w-full flex items-center justify-between">
                    <div>
                        <a wire:navigate href="{{ route('register') }}"
                           class="link link-primary"> Criar conta</a>
                        <br/>
                    </div>
                    <div class="space-x-3">
                        <x-button label="Resetar senha" class="btn-primary" type="submit" spinner="submit"/>
                    </div>
                </div>
            </x-slot:actions>


        </div>
    </x-form>

</x-card>

