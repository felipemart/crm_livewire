<x-card title="Login" shadow class="mx-auto w-[500px]">


    <x-form wire:submit="tryLogin">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Password" wire:model="password" type="password"/>

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
        <div class="w-full flex items-center justify-between">

            <x-slot:actions>
                <div class="w-full flex items-center justify-between">
                    <div>
                        <a wire:navigate href="#"
                           class="link link-primary"> {{ trans('message.create a account') }}</a>
                        <br/>
                        <a wire:navigate href="#"
                           class="link link-primary"> {{ trans('message.Forgot your password?') }}</a>
                    </div>
                    <div class="space-x-3">
                        <x-button label="Cancelar" type="reset"/>
                        <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
                    </div>
                </div>
            </x-slot:actions>


        </div>
    </x-form>

</x-card>
