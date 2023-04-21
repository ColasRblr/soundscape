var swiper = new Swiper(".swiper-container", {
  effect: "coverflow",
  grabCursor: true,
  // width: "100%",
  slidesPerView: 2,
  spaceBetween: 5,
  centeredSlides: true,
  coverflowEffect: {
    rotate: 50,
    stretch: 0,
    depth: 100,
    modifier: 1,
  },
  pagination: {
    el: ".swiper-pagination",
  },
  on: {
    init: function () {
      slideChange.call(this);
    },
    slideChange: slideChange,
  },
});

slideChange.call(swiper);

// Définition de la fonction slideChange

function slideChange() {
  // Récupérer l'ID de la catégorie de la nouvelle slide
  var category_id = this.slides[this.activeIndex].getAttribute("data-id"); // Envoyer l'ID de la catégorie au contrôleur Symfony via AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "/get-songs-by-category");
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.responseType = "json";
  xhr.onreadystatechange = function () {
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      var response = this.response;
      // Mettre à jour la liste de chansons avec les nouvelles chansons
      var songList = document.querySelector("#song-list");
      songList.innerHTML = "";
      response.forEach(function (song) {
        var li = document.createElement("li");
        var a = document.createElement("a");
        a.setAttribute("href", "/player/" + song.id + "/" + category_id);
        // Ajouter l'URL en tant que data-url
        a.innerHTML = "<h4>" + song.artist + "</h4><h5>" + song.title + "</h5>";
        li.appendChild(a);
        li.setAttribute("data-category-id", category_id);
        songList.appendChild(li);
      });
    }
  };
  xhr.send("category_id=" + category_id);
}
