<template>
    <div class="relative top-0 bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <Disclosure as="nav" class="bg-white shadow-md" v-slot="{ open }">
                <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                    <div class="relative flex h-16 items-center justify-between">
                        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                            <!-- Mobile menu button-->
                            <DisclosureButton
                                class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="absolute -inset-0.5" />
                                <span class="sr-only">Open main menu</span>
                                <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                                <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                            </DisclosureButton>
                        </div>
                        <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                            <div class="flex flex-shrink-0 items-center">
                                <!-- <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                            alt="Your Company" /> -->
                                <div class="bg-white h-10 w-11 m-1 rounded-full">
                                    <img src="/img/nuevo-logo1.png" alt="Logo" class="h-9 w-8 m-auto mt-0.5">
                                </div>

                            </div>
                            <div class="hidden sm:ml-6 sm:block ">
                                <div class="flex space-x-4 h-full items-center">
                                    <a v-for="item in navigation" :key="item.name" :href="item.href"
                                        :active="route().current('dashboard')"
                                        :class="[route().current(item.href) ? 'bg-white text-black' : 'text-gray-500 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']"
                                        :aria-current="item.current ? 'page' : undefined">{{ item.name }}
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="lg:flex-1 lg:justify-end">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                <div v-if="!canLogin" class="sm:fixed sm:right-0 p-6 text-end">
                                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                        class="font-semibold text-gray-900">
                                    Dashboard</Link>

                                    <template v-else>
                                        <Link :href="route('login')"
                                            class="text-gray-500 hover:text-gray-900 hover:font-bold px-3 my-auto text-sm font-medium">
                                        Log in</Link>

                                        <Link v-if="!canRegister" :href="route('register')"
                                            class="ms-4 text-gray-500 hover:text-gray-900 hover:font-bold px-3 text-sm font-medium">
                                        Register</Link>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <DisclosurePanel class="sm:hidden">
                    <div class="space-y-1 px-2 pb-3 pt-2">
                        <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                            :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']"
                            :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </header>
    </div>
    <main>
        <slot></slot>
    </main>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import DropdownLink from '@/Components/DropdownLink.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

const navigation = [
    { name: 'Dashboard', href: 'dashboard' },
    { name: 'Prueba', href: 'prueba' },
]

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    }
});
</script>
