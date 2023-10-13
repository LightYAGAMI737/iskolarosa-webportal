function toggleMenu() {
    var topnav = document.getElementById("myTopnav");
    var menu = document.getElementById("menu");
    
    if (topnav.className === "topnav") {
      topnav.className += " responsive";
      menu.classList.add("show-menu");
    } else {
      topnav.className = "topnav";
      menu.classList.remove("show-menu");
    }
  }
  