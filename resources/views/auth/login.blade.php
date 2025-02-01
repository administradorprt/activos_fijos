<x-guest-layout>

    <div class="container mb-5">
        <div class="row">

            <div class="col-md-3 my-5 text-right anim-right anim-pause-4">
                <h5 class="text-danger mb-2">
                    <span><b>{{ __('Visión:') }}</b></span>
                </h5>
                <h3 class="text-prt">
                    <b>
                        <span>{{ __('Ser un grupo') }}</span>
                        <span>{{ __('de empresas') }}</span>
                        <span>{{ __('sostenibles que se') }}</span>
                        <span>{{ __('caracterizan por') }}</span>
                        <span>{{ __('la excelencia en') }}</span>
                        <span>{{ __('el servicio.') }}</span>
                    </b>
                </h3>
            </div>

            <div class="col-xl-6 col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                <div class="card o-hidden border-0 shadow-lg my-5 animated animated-sm bounceIn">
                    <div class="card-body p-0">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="p-5">
                                    <form method="POST" class="user was-validated" id="loginForm">
                                        @csrf

                                        <div class="anim-up anim-pause-1 mb-4 col-md-12">
                                            <center>

                                                <x-logo width="300px"/>

                                                <div class="col-12 anim-right anim-pause-1">
                                                    <hr class="col-md-5">
                                                        <b>{{ __('Plataforma de') }}
                                                            <i class="text-danger">{{ __('activos') }}</i>
                                                        </b>
                                                    <hr class="col-md-5">
                                                </div>

                                            </center>
                                        </div>

                                        <input type="hidden" value="1" name="estatus" class="form-control-user" id="estatus" >

                                        <div class="form-group anim-up anim-pause-1">
                                            <x-input-label class="font-weight-bold" for="email" :value="__('N° Empleado:')" />
                                            <x-text-input id="email"
                                                class="block mt-1 w-full form-control form-control-user"
                                                type="text"
                                                name="n_empleado"
                                                :value="old('n_empleado')"
                                                placeholder="N° Empleado"
                                                required autofocus autocomplete="n_empleado" />
                                            <x-input-error :messages="$errors->get('n_empleado')" class="mt-2 badde badge-danger badge-pill" />

                                        </div>

                                        <div class="form-group anim-up anim-pause-1">
                                            <x-input-label class="font-weight-bold" for="password" :value="__('Contraseña:')" />

                                            <x-text-input id="password"
                                                class="block mt-1 w-full"
                                                type="password"
                                                name="password"
                                                class="form-control form-control-user"
                                                placeholder="Contraseña"
                                                required autocomplete="current-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="form-group anim-up anim-pause-1">
                                            <div class="custom-control small">
                                                <input type="checkbox" name="remember_me" class="custom-control-label ">
                                                <label class="text-secondary font-weight-bold" for="remember_me"> {{ __('Recordar contraseña') }} </label>
                                            </div>
                                        </div>

                                        <a class="bnt_spin anim-up anim-pause-1">
                                            <button type="submit" class="btn btn-dark btn-user btn-block" data-toggle="popover" data-content="Pulsa una vez de ingresar tus credenciales de acceso">
                                                <b id="message"> <i class="fa-solid fa-right-to-bracket"></i> {{ __('Iniciar Sesión') }} </b>
                                            </button>
                                        </a>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 my-5 text-left anim-left anim-pause-4">
                <h5 class="text-danger mb-2">
                    <span><b>{{ __('Misión:') }}</b></span>
                </h5>
                <h3 class="text-prt">
                    <b>
                        <span>{{ __('Brindar apoyo a') }}</span>
                        <span>{{ __('todos los asociados') }}</span>
                        <span>{{ __('para garantizar') }}</span>
                        <span>{{ __('el') }}</span>
                        <span>{{ __('mejor servicio.') }}</span>
                    </b>
                </h3>
            </div>

        </div>
    </div>

</x-guest-layout>


