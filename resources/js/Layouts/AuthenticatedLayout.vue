<template>
    <Disclosure as="nav" class="bg-white shadow-md" v-slot="{ open }">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <DisclosureButton
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="absolute -inset-0.5" />
                        <span class="sr-only">{{$t('Open main menu')}}</span>
                        <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="flex flex-1 items-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <div class="bg-white h-10 w-11 m-1 rounded-full">
                            <img src="/img/nuevo-logo1.png" alt="Logo" class="h-9 w-8 m-auto mt-0.5">
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:block ">
                        <div class="flex space-x-4 h-full items-center">
                            <a v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[route().current(item.href) ? 'bg-white text-blue-900 font-extrabold' : 'text-gray-300 hover:text-gray-600', 'rounded-md px-3 py-2 text-sm font-medium']"
                                :aria-current="route().current(item.href) ? 'page' : undefined">{{ item.name }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <div v-if="$page.props.auth.user" class="flex">
                        <button type="button"
                            class="relative rounded-full bg-white0 p-1 text-gray-400 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="absolute -inset-1.5" />
                            <span class="sr-only">{{$t('View notifications')}}</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>
                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div>
                                <MenuButton
                                    class="relative flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                    <span class="absolute -inset-1.5" />
                                    <span class="sr-only">{{$t('Open user menu')}}</span>
                                    <img class="h-8 w-8 rounded-full" src="/img/user.png" alt="" />
                                </MenuButton>
                            </div>
                            <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <MenuItem v-slot="{ active }">
                                    <a href="#"
                                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-900']">{{$t('Your Profile')}}</a>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <a href="#"
                                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-900']">{{$t('Settings')}}</a>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                    <DropdownLink :href="route('logout')" method="post" as="button"
                                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-900']">
                                        {{$t('Sign out')}}
                                    </DropdownLink>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                    <div v-else class="flex">
                        <Link :href="route('login')"
                            class="text-gray-500 hover:text-gray-900 hover:font-bold px-3 my-auto text-sm font-medium">
                        {{$t('Log in')}}</Link>


                        <Link :href="route('register')"
                            class="ms-4 text-gray-500 hover:text-gray-900 hover:font-bold px-3 text-sm font-medium">
                        {{$t('Register')}}</Link>
                    </div>
                </div>
            </div>
        </div>
        <DisclosurePanel class="sm:hidden">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                    :class="[route().current(item.href) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']"
                    :aria-current="route().current(item.href) ? 'page' : undefined">{{ item.name }}</DisclosureButton>
            </div>
        </DisclosurePanel>
    </Disclosure>
    <!-- Page Content -->
    <main>
        <slot />
    </main>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import DropdownLink from '@/Components/DropdownLink.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const navigation = [
    { name: 'Bookmarks', href: 'bookmarks' },
    { name: 'Search Advanced', href: 'searchadvanced' }
];

defineProps({
    authenticated: {
        type: Boolean,
    },
});
</script>
