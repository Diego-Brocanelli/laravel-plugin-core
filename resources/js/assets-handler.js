
export default class AssetsHandler {
  
    static replacePageScript(id, content)
    {
      let currentScript = document.getElementById(id);
      if (currentScript) {
        currentScript.parentElement.removeChild(currentScript); 
      }
  
      let script = document.createElement("script"); 
      script.text = content;
      script.setAttribute('id', id);
      document.body.appendChild(script);
    }

    static replacePageStyle(id, content)
    {
      let currentStyle = document.getElementById(id);
      if (currentStyle) {
        currentStyle.parentElement.removeChild(currentStyle); 
      }
  
      let style = document.createElement("style"); 
      style.appendChild(document.createTextNode(content));
      style.setAttribute('id', id);
      style.setAttribute('type', 'text/css');
      document.body.appendChild(style);
    }
}