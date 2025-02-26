<div>


    <x-card title="Password Recovery" shadow class="mx-auto w-[500px]">
        <span class="text-gray-600 dark:text-gray-400 text-sm">
Insira o endereço de e-mail verificado da sua conta de usuário e enviaremos um link para redefinição de senha.</span>
        <x-form wire:submit="recoverPassword" class="mt-4">
            <x-input label="Email" wire:model="email"/>

            @if(session()->has('status'))
                <x-alert icon="o-exclamation-triangle" class="alert-warning">
                    <span>{{ session()->get('status') }}</span>
                </x-alert>
            @endif

            @if($message)
                <x-alert icon="o-exclamation-triangle" class="alert-success">
                    {{$message}}
                </x-alert>
            @endif

            <div class="w-full flex items-center justify-between">

                <x-slot:actions>
                    <div class="w-full flex items-center justify-between">
                        <div>
                            <a wire:navigate href="{{ route('login') }}"
                               class="link link-primary"> Login </a>
                            <br/>
                            <a wire:navigate href="{{ route('register') }}"
                               class="link link-primary"> Criar conta</a>
                            <br/>
                        </div>
                        <div class="space-x-3">
                            <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
                        </div>
                    </div>
                </x-slot:actions>


            </div>
        </x-form>

    </x-card>


</div>
