<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen grid grid-cols-12 gap-4 pt-6 sm:pt-0 bg-gray-100">
        <div class="col-span-12 flex justify-center items-center">
            <div class="col-span-12 md:col-span-8 lg:col-span-6 flex flex-col items-center">
                <div>
                    <Link href="/">
                        <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
                    </Link>
                </div>

                <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
