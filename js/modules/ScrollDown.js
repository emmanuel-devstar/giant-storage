class ScrollDown {
    constructor() {          
      try {
        document.querySelector(".scroll-js").addEventListener("click", () => this.scroll());
      }
      catch(err) {
        //console.log(err.message);
      }      
    }   

    scroll(){                
        let offset = document.querySelector(".scroll-to-js").offsetTop;
        window.scroll({
          top: offset-80,
          behavior: 'smooth' 
        });        
    }
        
  }
  
  export default ScrollDown