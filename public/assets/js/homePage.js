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
  songList.classList.add("hidden"); // Masquer la liste des chansons

  $.ajax({
    url: "/category/" + category_id + "/getsongs",
    success: function (response) {
      songList.innerHTML = "";
      response.forEach(function (song) {
        var li = document.createElement("li");
        var a = document.createElement("a");
        a.setAttribute("href", "/player/" + song.id + "/" + category_id);
        a.classList.add("homeSong");
        a.innerHTML = "<h4 id ='home-song-artist'> " + song.artist + "</h4><h5 id ='home-song-title'>" + song.title + "</h5>";
        li.appendChild(a);
        songList.appendChild(li);
      });
      songList.classList.remove("hidden"); // Afficher la liste des chansons
    },
  });
}
