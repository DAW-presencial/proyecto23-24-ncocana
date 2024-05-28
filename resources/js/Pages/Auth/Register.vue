<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios'; // Asegúrate de importar axios
import { onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = async () => {
    form.post(route('register'), {
        onSuccess: () => login(),
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const login = async () => {
    let params = {
        "email": form.email, // Usa form.email
        "password": form.password // Usa form.password
    }
    console.log(JSON.stringify(params));

    await axios.post('/api/v1/login', JSON.stringify(params))
        .then(response => {
            console.log(response);
            const resultado = response.data;
            localStorage.setItem('token', resultado.token_type + ' ' + resultado.access_token);
        }).catch(error => {
            console.error("Error al iniciar sesión:", error);
        });
}

onMounted(() => {
    localStorage.removeItem('token');
});
</script>

<template>
    <GuestLayout>

        <Head title="Register" />
        <h2 class=" mb-4 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{$t('Sign up')}}</h2>


        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" :value="$t('Name')" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" :value="$t('Email')" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="$t('Password')" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" :value="$t('Confirm Password')" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{$t('Already registered?')}}
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{$t('Register')}}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
