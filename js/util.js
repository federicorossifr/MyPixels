function empty(element) {
    while(element.hasChildNodes())
      element.removeChild(element.firstChild);
  }