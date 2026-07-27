<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="min-h-screen flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-md">

            
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden border border-white/30">

                
                <div class="bg-gradient-to-r from-sky-500 to-sky-700 text-white text-center py-5 px-6">

                    <div class="flex justify-center mb-4">

                        
                        <div class="w-11 h-11 rounded-full bg-white shadow flex items-center justify-center">

                            <?php if(file_exists(public_path('storage/logo.png'))): ?>
                                <img src="<?php echo e(asset('storage/logo.png')); ?>"
                                     class="w-11 h-11 object-contain">
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-10 h-10 text-sky-600"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z"/>
                                </svg>
                            <?php endif; ?>

                        </div>

                    </div>

                    <h1 class="text-xl font-bold tracking-wide">
                        EKKLESIA GESTION
                    </h1>

                    

                </div>

                
                
                <div class="px-8 py-5">

                    <h2 class="text-lg font-bold text-center text-gray-800">
                        Bienvenue
                    </h2>

                    <p class="text-center text-gray-500 text-sm mt-1 mb-5">
                        Accédez à votre espace sécurisé.
                    </p>

                    <?php if(session('warning')): ?>
                        <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                            <?php echo e(session('warning')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        
                        <div class="relative">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A9 9 0 1118.364 4.56M15 11a3 3 0 11-6 0 3 3 0 016 0zm-3 5c-2.21 0-4 1.79-4 4h8c0-2.21-1.79-4-4-4z"/>
                            </svg>

                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'username','class' => 'w-full rounded-xl pl-11 py-3','type' => 'text','name' => 'username','value' => old('username'),'placeholder' => 'Nom d\'utilisateur','required' => true,'autofocus' => true,'autocomplete' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'username','class' => 'w-full rounded-xl pl-11 py-3','type' => 'text','name' => 'username','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('username')),'placeholder' => 'Nom d\'utilisateur','required' => true,'autofocus' => true,'autocomplete' => 'username']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>

                        </div>

                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('username'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('username')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>

                        
                        <div class="relative mt-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v3H6a2 2 0 00-2 2v5a2 2 0 002 2zm3-9V9a3 3 0 016 0v3"/>
                            </svg>

                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'password','class' => 'w-full rounded-xl pl-11 py-3','type' => 'password','name' => 'password','placeholder' => 'Mot de passe','required' => true,'autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password','class' => 'w-full rounded-xl pl-11 py-3','type' => 'password','name' => 'password','placeholder' => 'Mot de passe','required' => true,'autocomplete' => 'current-password']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>

                        </div>

                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>

                        <div class="flex items-center justify-between mt-4">

                            <label class="inline-flex items-center text-sm text-gray-600">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-sky-600 focus:ring-sky-500">

                                <span class="ml-2">
                                    Se souvenir
                                </span>

                            </label>

                        </div>

                        <button
                            type="submit"
                            class="mt-5 w-full rounded-xl bg-sky-500 py-3 text-white font-semibold shadow-lg transition duration-200 hover:bg-sky-700">

                            Se connecter

                        </button>

                    </form>

                </div>

                
                <div class="bg-gray-50 px-8 py-4 text-center">

                    <div class="w-14 h-1 bg-yellow-500 rounded mx-auto mb-2"></div>

                    <p class="italic text-gray-600 text-xs">
                        « Mais que tout se fasse avec bienséance et avec ordre. »
                    </p>

                    <p class="mt-1 font-semibold text-yellow-600 text-sm">
                        1 Corinthiens 14:40
                    </p>

                </div>

            </div>

            <p class="text-center text-yellow-600 text-xs mt-6">

                © <?php echo e(date('Y')); ?> • Eglise AEBECI Yopougon Nouveau Bureau

            </p>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\ekklesia-gestion\resources\views/auth/login.blade.php ENDPATH**/ ?>