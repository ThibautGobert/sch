<script setup>
import { RichTextEditorComponent as EjsRichtexteditor,Toolbar,Link,Image,HtmlEditor, QuickToolbar  } from "@syncfusion/ej2-vue-richtexteditor";
import {onMounted, provide, ref, watchEffect} from "vue";
import {EditorView, keymap, lineNumbers} from "@codemirror/view";
import {EditorState} from "@codemirror/state";
import {autocompletion} from "@codemirror/autocomplete";
import {html} from "@codemirror/lang-html";
import {oneDark} from "@codemirror/theme-one-dark";
import {indentWithTab} from "@codemirror/commands";
import {expandAbbreviation} from "@emmetio/codemirror6-plugin";
const props = defineProps({
    content: {
        required: false,
        type: String,
        default() {
            return ''
        }
    },
    toolbarSettings: {
        required: false,
        type: Object,
        default() {
            return {
                items: [ 'Bold', 'Italic', 'Underline', 'FontName', 'FontSize', 'FontColor', 'Formats', 'Alignments', 'CreateLink', 'Image', 'Undo', 'Redo', 'FullScreen', 'SourceCode']
            }
        },
    },
    imageSettings :{
        required: false,
        type: Object,
        default() {
            return {
                saveFormat: "Base64"
            }
        }
    },
})
const root = ref(null)
const value = ref(null)
const editor = ref(null)
const theme = ref('tailwind')
const myCodeMirror = ref(new EditorView({}))

//const richtexteditor =  [Toolbar, Link, Image, HtmlEditor, QuickToolbar];
const richtexteditor =  [Toolbar, Link, Image, HtmlEditor];
provide('richtexteditor', richtexteditor);
const emit = defineEmits(['update-state']);
const updateContent = (e)=> {
    let content = editor.value.ej2Instances.getHtml()
    window.dispatchEvent(new CustomEvent('update-state', { detail: content }))
}

const refreshUi = ()=> {
    if(editor.value) {
        editor.value.ej2Instances.refreshUI()
    }
}


const mirrorConversion = (e)=> {
    let textArea = editor.value.ej2Instances.contentModule.getEditPanel();
    let id = editor.value.ej2Instances.getID() +  'mirror-view';
    let mirrorView = editor.value.$el.parentNode.querySelector('#' + id);
    if (e.targetItem === 'Preview') {
        textArea.style.display = 'block';
        mirrorView.style.display = 'none';
        textArea.innerHTML = myCodeMirror.value.state.doc.toString();
        updateContent()
    } else {
        if (!mirrorView) {
            mirrorView = document.createElement('div', { className: 'e-content' });
            mirrorView.id = id;
            textArea.parentNode.appendChild(mirrorView);
        } else {
            mirrorView.innerHTML = '';
        }
        textArea.style.display = 'none';
        mirrorView.style.display = 'block';
        renderCodeMirror(mirrorView, editor.value.ej2Instances.getHtml());
    }
}
const renderCodeMirror = (mirrorView, content)=> {
    myCodeMirror.value = new EditorView({
        state: EditorState.create({
            doc: content,
            extensions: [
                autocompletion(),
                html(),
                lineNumbers(),
                oneDark,
                keymap.of([indentWithTab]),
                keymap.of([{
                    key: 'Mod-e',
                    run: expandAbbreviation,
                    preventDefault: true
                }]),
            ],
        }),
        parent: mirrorView,
    });
}
const actionCompleteHandler = (e)=> {
    if (e.targetItem && (e.targetItem === 'SourceCode' || e.targetItem === 'Preview')) {
        editor.value.ej2Instances.sourceCodeModule.getPanel().style.display = 'none';
        mirrorConversion(e);
    }
    else {
        setTimeout(()=> {
            editor.value.ej2Instances.toolbarModule.refreshToolbarOverflow();
        }, 400);
    }
}

onMounted(()=> {
    editor.value.ej2Instances.value = props.content
    theme.value = localStorage.getItem('theme') === 'light' ? 'tailwind' : 'tailwind-dark'
    window.addEventListener('theme-changed', (event) => {
        theme.value = event.detail === 'light' ? 'tailwind' : 'tailwind-dark'
    })
    //refreshUi()
})
const appendStyleFiles = (value)=> {
    const modules = [
        'ej2-base',
        'ej2-inputs',
        'ej2-lists',
        'ej2-popups',
        'ej2-buttons',
        'ej2-navigations',
        'ej2-splitbuttons',
        'ej2-vue-richtexteditor',
    ]
    modules.forEach(m=> {
        let file = document.createElement('link');
        file.id = m + '-' + value
        file.rel = 'stylesheet';
        file.href = `/css/syncfusion/${m}/styles/${value}.css`
        document.head.appendChild(file)
        let toRemove = value === 'tailwind' ? 'talwind-dark' : 'tailwind'
        document.getElementById(m + '-' + toRemove)?.remove()

    })
}
watchEffect(() => {
    appendStyleFiles(theme.value)
});

</script>

<template>
    <div>
        <div class="control-section" @keydown.enter.prevent.stop="()=>{}">
            <div class="sample-container">
                <div class="default-section" :class="theme">
                    <ejs-richtexteditor
                        ref="editor"
                        id="default"
                        @change="updateContent"
                        :actionComplete="actionCompleteHandler"
                        :toolbarSettings="toolbarSettings"
                        :insertImageSettings="imageSettings"
                    ></ejs-richtexteditor>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#default_toolbar_FontColor_Target {
    display: none;
}
/*
@import "@syncfusion/ej2-base/styles/tailwind.css";
@import "@syncfusion/ej2-inputs/styles/tailwind.css";
@import "@syncfusion/ej2-lists/styles/tailwind.css";
@import "@syncfusion/ej2-popups/styles/tailwind.css";
@import "@syncfusion/ej2-buttons/styles/tailwind.css";
@import "@syncfusion/ej2-navigations/styles/tailwind.css";
@import "@syncfusion/ej2-splitbuttons/styles/tailwind.css";

 */
/*
@import "@syncfusion/ej2-vue-richtexteditor/styles/tailwind.css";
@import "@syncfusion/ej2-vue-richtexteditor/styles/tailwind-dark.css";

 */
</style>
