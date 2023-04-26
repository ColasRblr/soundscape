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
  var category_id = this.slides[this.activeIndex].getAttribute("data-id");
  var songList = document.querySelector("#home-song-list");
  // songList.classList.add("hidden"); // Masquer la liste des chansons
  const authenticatedStatus = document.getElementById("authenticated-status");
  const isAuthenticated = authenticatedStatus.dataset.authenticated === "true";

  $.ajax({
    url: "/category/" + category_id + "/getsongs",
    success: function (response) {
      songList.innerHTML = "";

      response.forEach(function (song) {
        var li = document.createElement("li");
        li.classList.add("homeSongLi");
        var h4 = document.createElement("h4");
        h4.setAttribute("id", "home-song-artist");
        h4.innerText = song.artist;
        var h5 = document.createElement("h5");
        h5.setAttribute("id", "home-song-title");
        h5.innerText = song.title;
        var a = document.createElement("a");
        a.setAttribute("href", "/player/" + song.id + "/" + category_id);
        a.classList.add("homeSong");
        a.innerHTML = '<i class="bi bi-play-fill"></i>';
        li.appendChild(h4);
        li.appendChild(h5);
        li.appendChild(a);
        songList.appendChild(li);
        if (isAuthenticated) {
          var favbtn = document.createElement("a");
          var img = document.createElement("img");
          li.appendChild(favbtn);
          favbtn.appendChild(img);
          img.setAttribute("id", "favorite-btn");
          favbtn.classList.add("home-container-btn");
          favbtn.setAttribute("href", "/favorite/newFavorite/" + song.id);
          // Effectuer une requête AJAX pour vérifier si la chanson est dans les favoris de l'utilisateur
          $.ajax({
            url: "/favorite/isFavorite/" + song.id,
            success: function (response) {
              if (response == "true") {
                // La chanson est déjà dans les favoris, afficher le cœur rempli
                img.setAttribute("src", "/img/favorite.png");
              } else {
                // La chanson n'est pas dans les favoris, afficher le cœur vide
                img.setAttribute("src", "/img/emptyHeart.png");
              }
            },
          });
        }
      });
      // songList.classList.remove("hidden"); // Afficher la liste des chansons
    },
  });
}
