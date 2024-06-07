<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import axios from 'axios';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    login();
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const login = async () => {
    let params = {
        "email": email.value,
        "password": password.value
    }
    // console.log(JSON.stringify(params));

    await axios.post('/api/v1/login', JSON.stringify(params))
        .then(response => {
            // console.log(response);
            const resultado = response.data;
            localStorage.setItem('token', resultado.token_type + ' ' + resultado.access_token);
        })
}

onMounted(() => {
    localStorage.removeItem('token');
});

</script>

<template>
    <div class="flex h-screen flex-1 flex-col justify-center px-6 py-12 lg:px-8">
        <GuestLayout>
            <div class="flex justify-center">
                <LanguageSwitcher />
            </div>

            <Head title="Log in" />
            <h2 class=" mb-4 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{$t('Sign in to your account')}}</h2>


            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ $t(status)}}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" :value="$t('Email address')" />

                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password">


                        <div class="text-sm">
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="font-semibold text-indigo-600 hover:text-indigo-500">
                                {{$t('Forgot password?')}}
                            </Link>

                            <!-- <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a> -->
                        </div>
                    </InputLabel>

                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                        autocomplete="current-password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">{{$t('Remember me')}}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">

                    <PrimaryButton class="w-100 m-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        {{$t('Sign in')}}
                    </PrimaryButton>
                </div>
                <div class="flex items-center justify-end mt-4 mx-auto">
                    <p class="text-sm text-gray-600 m-auto">{{ $t('¿No tienes una cuenta?') }}
                        <Link :href="route('register')"
                            class="ml-2 font-semibold text-indigo-600 hover:text-indigo-500 ">
                        {{$t('Regístrate')}}</Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    </div>
</template>
