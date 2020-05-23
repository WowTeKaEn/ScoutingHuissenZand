import Quill from "quill";
export function convertHtmlToDelta(editorId){
    return new Quill(editorId, {
        theme: 'bubble'
      }).getContents();
}
