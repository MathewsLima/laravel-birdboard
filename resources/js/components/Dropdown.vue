<template>
    <div class="dropdown relative">
        <div
            @click="toggle"
            aria-haspopup="true"
            :aria-expanded="isOpen"
        >
            <slot name="trigger"></slot>
        </div>

        <div
            class="absolute bg-card py-2 rounded shadow mt-2"
            :class="alignType"
            :style="{ width }"
            v-show="isOpen">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        width: {
            type: String,
            default: 'auto'
        },
        align: {
            type: String,
            default: 'left'
        }
    },
    data: () => ({
        isOpen: false
    }),
    computed: {
        alignType() {
            return this.align === 'left' ? 'pin-l' : 'pin-r';
        }
    },
    watch: {
        isOpen(itIsOpened) {
            if (itIsOpened) {
                document.addEventListener('click', this.closeIfClickOutside);
            }
        }
    },
    methods: {
        toggle() {
            this.isOpen = !this.isOpen;
        },
        closeIfClickOutside(event) {
            console.log('hi')
            if (! event.target.closest('.dropdown')) {
                this.isOpen = false;

                document.removeEventListener('click', this.closeIfClickOutside);
            }
        }
    }
};
</script>

<style>
</style>
