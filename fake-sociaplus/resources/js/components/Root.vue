<template>
    <v-app>
        <v-app-bar app dark>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
            <v-toolbar-title>Football Manager</v-toolbar-title>
        </v-app-bar>
        <v-main>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-main>
        <v-footer>
            Footer
        </v-footer>
    </v-app>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
