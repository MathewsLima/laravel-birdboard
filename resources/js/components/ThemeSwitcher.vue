<template>
    <div class="flex items-center mr-8">
        <button v-for="(color, theme) in themes" :key="theme"
            class="rounded-full w-4 h-4 bg-default-color border mr-2 focus:outline-none"
            :class="{'border-accent': selectedTheme === theme}"
            :style="{backgroundColor: color}"
            @click="changeTheme(theme)">
        </button>
    </div>
</template>

<script>
export default {
    data: () => ({
        selectedTheme: 'theme-light',
        themes: {
            'theme-light': '#f5f6f9',
            'theme-dark': '#222',
        }
    }),
    created() {
        this.selectedTheme = localStorage.getItem('theme') || 'theme-light';
    },
    watch: {
        selectedTheme() {
            document.body.className =
                document.body.className.replace(/theme-\w+/gi, this.selectedTheme);
        }
    },
    methods: {
        changeTheme(theme) {
            this.selectedTheme = theme;

            localStorage.setItem('theme', this.selectedTheme);
        }
    }
}
</script>
