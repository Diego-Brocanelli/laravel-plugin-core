
export default class AssetsHandler {

  constructor(app) {
    this.app = app
  }

  appendScriptUrl(url) {

    let scripts = document.createElement("script")
    scripts.setAttribute('class', 'state-script')
    scripts.setAttribute('src', url + '?v=' + (new Date().getTime()))
    document.body.appendChild(scripts)
    
  }

  appendStyleUrl(url) {

    let style = document.createElement("link")
    style.setAttribute('rel', 'stylesheet')
    style.setAttribute('class', 'state-class')
    style.setAttribute('href', url + '?v=' + (new Date().getTime()))
    document.head.appendChild(style)

  }

  replacePageScript(id, content) {

    let currentScript = document.getElementById(id)
    if (currentScript) {
      currentScript.parentElement.removeChild(currentScript)
    }

    let script = document.createElement("script")
    script.text = content
    script.setAttribute('id', id)
    document.body.appendChild(script)
  }

  replacePageStyle(id, content) {

    let currentStyle = document.getElementById(id)
    if (currentStyle) {
      currentStyle.parentElement.removeChild(currentStyle)
    }

    let style = document.createElement("style")
    style.appendChild(document.createTextNode(content))
    style.setAttribute('id', id)
    style.setAttribute('type', 'text/css')
    document.body.appendChild(style)
  }

  applyAppStyles(styles) {

    if (styles === undefined) {
      return
    }

    var elements = document.querySelectorAll('.state-class')
    elements.forEach(item => { item.remove(); })

    styles.forEach(href => {

      let style = document.createElement("link")
      style.setAttribute('rel', 'stylesheet')
      style.setAttribute('class', 'state-class')
      style.setAttribute('href', href + '?v=' + (new Date().getTime()))
      document.head.appendChild(style)
    });

  }

  applyAppScripts(scripts) {

    if (scripts === undefined) {
      return
    }

    var elements = document.querySelectorAll('.state-script')
    elements.forEach(item => { item.remove(); })

    scripts.forEach(src => {

      let scripts = document.createElement("script")
      scripts.setAttribute('class', 'state-script')
      scripts.setAttribute('src', src + '?v=' + (new Date().getTime()))
      document.body.appendChild(scripts)
    });
  }
}