<template>
    <dropdown class="ml-5 h-9 flex items-center dropdown-right">
        <dropdown-trigger class="h-9 flex items-center">
            <div class="mr-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                >
                    <path
                        class="heroicon-ui"
                        d="M4.06 13a8 8 0 0 0 5.18 6.51A18.5 18.5 0 0 1 8.02 13H4.06zm0-2h3.96a18.5 18.5 0 0 1 1.22-6.51A8 8 0 0 0 4.06 11zm15.88 0a8 8 0 0 0-5.18-6.51A18.5 18.5 0 0 1 15.98 11h3.96zm0 2h-3.96a18.5 18.5 0 0 1-1.22 6.51A8 8 0 0 0 19.94 13zm-9.92 0c.16 3.95 1.23 7 1.98 7s1.82-3.05 1.98-7h-3.96zm0-2h3.96c-.16-3.95-1.23-7-1.98-7s-1.82 3.05-1.98 7zM12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"
                    />
                </svg>
            </div>

            <span class="text-90">
                {{ currentLocaleName }} ({{ currentLocale }})
            </span>
        </dropdown-trigger>

        <dropdown-menu slot="menu" width="200" direction="rtl">
            <ul class="list-reset" style="max-height: 50vh; overflow-y: auto;">
                <li v-for="(language, locale) in locales" :key="locale" :data="locale">
                    <a
                        @click.prevent="switchLocale(locale)"
                        class="block no-underline text-90 hover:bg-30 p-3 cursor-pointer"
                    >
                        {{ language }} ({{ locale }})
                    </a>
                </li>
            </ul>
        </dropdown-menu>
    </dropdown>
</template>

<script>
    export default {
        data: () => ({
            locales: []
        }),

        mounted() {
            Nova.request()
                .get(`/nova-vendor/locale-anywhere/languages`)
                .then(response => {
                    this.locales = response.data
                })
        },

        methods: {
            switchLocale(locale) {
                Nova.request()
                    .post(`/nova-vendor/locale-anywhere/cache-locale`, {
                        locale: locale
                    })
                    .then(() => {
                        window.location.reload()
                    })
            }
        },

        computed: {
            currentLocale() {
                return Nova.config.localeAnywhere.currentLocale ? Nova.config.localeAnywhere.currentLocale : 'en'
            },
            currentLocaleName() {
                let name = null

                Object.keys(this.locales).forEach(locale => {
                    if (locale === Nova.config.localeAnywhere.currentLocale) {
                        name = this.locales[locale]
                    }
                })

                return name
            }
        }
    }
</script>
<style></style>
