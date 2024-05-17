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
    login()
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const login = () => {
    let params = {
        "email": email.value,
        "password": password.value
    }
    console.log(JSON.stringify(params));

    axios.post('/login', JSON.stringify(params))
        .then(response => {
            console.log(response);
            const resultado = response.data;
            localStorage.setItem('token', resultado.token_type + ' ' + resultado.access_token);
        })
}

</script>

<template>
    <div class="flex h-screen flex-1 flex-col justify-center px-6 py-12 lg:px-8">
        <GuestLayout>

            <Head title="Log in" />
            <h2 class=" mb-4 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your
                account</h2>


            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email address" />

                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password">


                        <div class="text-sm">
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="font-semibold text-indigo-600 hover:text-indigo-500">
                            Forgot password?
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
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">

                    <PrimaryButton class="w-100 m-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Sign in
                    </PrimaryButton>
                </div>
                <div class="flex items-center justify-end mt-4 mx-auto">
                    <p class="text-sm text-gray-600 m-auto">¿No tienes una cuenta?
                        <Link :href="route('register')"
                            class="ml-2 font-semibold text-indigo-600 hover:text-indigo-500 ">
                        Regístrate</Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    </div>
</template>
