<template>
    <div id="app-content">
        <div class="app-content-left apps">
            <breadcrumb :show-add-new="false"></breadcrumb>

            <div class="app-overview">
                <translate say="Browsers" tag="h1" icon="globe"/>
                <translate say="Android" tag="h1" icon="android"/>
                <div class="app-list">
                    <a class="app" target="_blank" rel="noreferrer noopener" v-for="(app, id) in getBrowserExtensions" :class="id" :href="app.download">
                        <translate :say="app.label" tag="h3"/>
                        <web target="_blank" class="author" :class="{'fa fa-certificate':app.official}" :href="app.sources" :text="app.author"/>
                        <translate :say="app.description" tag="div" class="description"/>
                    </a>
                </div>

                <div class="app-list">
                    <a class="app" target="_blank" rel="noreferrer noopener" v-for="(app, id) in getAndroidApps" :class="[id, app.legacy ? 'legacy':'']" :href="app.download">
                        <translate :say="app.label" tag="h3"/>
                        <web target="_blank" class="author" :href="app.web" :text="app.author"/>
                        <span class="dot">⦁</span>
                        <web target="_blank" class="author" :href="app.sources" text="sources"/>
                        <translate :say="app.description" tag="div" class="description"/>
                        <translate say="This app uses an api which is no longer supported." tag="div" class="legacy" v-if="app.legacy"/>
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Web from '@vue/Components/Web';
    import Translate from '@vue/Components/Translate';
    import Breadcrumb from '@vue/Components/Breadcrumb';
    import Localisation from '@js/Classes/Localisation';

    export default {
        components: {
            Web,
            Breadcrumb,
            Translate
        },

        computed: {
            getBrowserExtensions() {
                return {
                    "firefox": {
                        "label"      : "Official Firefox Client",
                        "author"     : Localisation.translate("official"),
                        "description": "Access and manage all your passwords easily within Firefox thanks to our official extension from the Firefox Add-on store.",
                        "download"   : "https://addons.mozilla.org/firefox/addon/nextcloud-passwords?src=external-apps",
                        "sources"    : "https://github.com/marius-wieschollek/passwords-webextension",
                        "official"   : true
                    },
                    "chrome" : {
                        "label"      : "Official Chrome Client",
                        "author"     : Localisation.translate("official"),
                        "description": "Our official Chrome extension lets you manage all your passwords from your browser and is available for many Chromium based Browsers from the Chrome Web Store.",
                        "download"   : "https://chrome.google.com/webstore/detail/nextcloud-passwords/mhajlicjhgoofheldnmollgbgjheenbi",
                        "sources"    : "https://github.com/marius-wieschollek/passwords-webextension",
                        "official"   : true
                    }
                };
            },
            getAndroidApps() {
                return {
                    "daper"  : {
                        "label"      : "Nextcloud Passwords",
                        "author"     : Localisation.translate("created by {author}", {author: "daper"}),
                        "description": "Finally a modern, fast and lightweight app to access and manage your passwords from your Android device. Get it from Google Play.",
                        "download"   : "https://play.google.com/store/apps/details?id=com.nextcloudpasswords",
                        "sources"    : "https://github.com/daper/nextcloud-passwords-app",
                        "web"        : "https://github.com/daper",
                        "legacy"     : false
                    },
                    "intirix": {
                        "label"      : "Cloud Password Manager",
                        "author"     : Localisation.translate("created by {author}", {author: "intirix"}),
                        "description": "Cloud Password Manager is a password manager that puts you in control. Access all the passwords stored on your Nextcloud from your Android Phone.",
                        "download"   : "https://play.google.com/store/apps/details?id=com.intirix.cloudpasswordmanager",
                        "sources"    : "https://github.com/intirix/cloudpasswordmanager",
                        "web"        : "https://github.com/intirix",
                        "legacy"     : true
                    }
                };
            }
        }
    };
</script>

<style lang="scss">
    .app-content-left.apps {
        .app-overview {
            padding         : 0 1rem 1rem;
            display         : grid;
            grid-template   : "hBrowser hAndroid" "cBrowser cAndroid";
            grid-column-gap : 1rem;
            grid-row-gap    : 1rem;

            @media(max-width : $width-extra-small) {
                grid-template : "hAndroid" "cAndroid" "hBrowser" "cBrowser";
            }
        }

        h1 {
            font-size   : 2rem;
            font-weight : bold;
            line-height : 2rem;
            margin      : 2rem 0 0.5rem;
            grid-area   : hBrowser;

            &:nth-child(2) {
                grid-area : hAndroid;
            }
        }

        .app-list {
            display               : grid;
            grid-template-columns : 1fr 1fr;
            grid-column-gap       : 1rem;
            grid-row-gap          : 1rem;
            grid-area             : cBrowser;

            &:nth-child(4) {
                grid-area : cAndroid;
            }

            .app {
                display         : block;
                border-radius   : var(--border-radius);
                padding         : 200px 0.5rem 0.5rem;
                background      : url(../../img/browser/firefox.svg) no-repeat center 20px;
                background-size : 160px;
                border          : 1px solid #0000;
                box-sizing      : border-box;
                transition      : border-color 0.15s ease-in-out;

                h3 {
                    font-size     : 1.25rem;
                    font-weight   : bold;
                    margin-bottom : 0;
                }

                .author {
                    font-style : italic;

                    &.fa.fa-certificate {
                        color       : var(--color-success) !important;
                        font-family : var(--font-face);

                        &:before {
                            font-family : var(--pw-icon-font-face);
                            font-style  : normal;
                        }
                    }

                    &:hover {
                        text-decoration : underline;
                    }
                }

                .description {
                    margin-top : 0.5rem;
                }

                .legacy {
                    font-weight : bold;
                }

                .dot {
                    color  : var(--color-primary);
                    margin : 0 0.5rem;
                }

                &.chrome {
                    background-image : url(../../img/browser/chrome.svg);
                }

                &.daper {
                    background-image : url(../../img/apps/daper.png);
                }

                &.intirix {
                    background-image : url(../../img/apps/intirix.png);
                }

                &.legacy {
                    background-color : var(--color-loading-light);
                    opacity          : 0.6;
                    transition       : opacity 0.15s ease-in-out;

                    &:hover {
                        opacity : 1;
                    }
                }

                &:hover {
                    border : 1px solid var(--color-primary);
                }
            }

            @media(max-width : $width-large) {
                grid-template-columns : 1fr;
            }
        }
    }

</style>