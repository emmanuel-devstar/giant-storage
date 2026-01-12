class StickyHeader {
    constructor() {
      this.siteHeader = document.querySelector(".c-nav-top");
      
      document.addEventListener("scroll", () => this.scroll());
    }
  
    scroll() {            
      if (window.scrollY > 60) {
        this.siteHeader.classList.add("sticky");    
        if(this.siteHeader.classList.contains("section-transparent")){
          this.siteHeader.classList.replace("section-transparent", "section-transparent-tmp");
        }
                    
      } else {
        this.siteHeader.classList.remove("sticky");
        if(this.siteHeader.classList.contains("section-transparent-tmp")){
          this.siteHeader.classList.replace("section-transparent-tmp", "section-transparent");
        }
      }
    }
  }
  
  export default StickyHeader