import { createApp } from 'vue'
import CustomRichText from "./CustomRichText.vue";
import { registerLicense } from '@syncfusion/ej2-base';

registerLicense('ORg4AjUWIQA/Gnt2UFhhQlJBfV5AQmBIYVp/TGpJfl96cVxMZVVBJAtUQF1hTX5Qd0BiUHtZcnFcQGVY');


let apps = []
const renderApp = () => {
    // Réinitialiser apps chaque fois que renderApp est appelé
    apps.forEach(app => app.unmount());
    apps = []; // Réinitialiser le tableau après les avoir désinstallées
        Array.from(document.getElementsByClassName('custom-rich-text')).forEach((el) => {
            const app = createApp(CustomRichText, { content: el.dataset.content });
            app.mount(el);
            apps.push(app);
        });
};

addEventListener("DOMContentLoaded", (event) => {
    renderApp()
})

Livewire.hook('morph.removed',  ({ el }) => {
    renderApp()
})

