let sort_params = "";

let filter_params = {
  type: "",
  prices: "",
  capacity: "",
};

const api = "http://localhost/api/router.php/rooms";

const template = document.getElementById("room-template");

const list_rooms = document.querySelector(".list-rooms");

const list_imgs = ["images/room.jpg", "images/pool_inside.jpg"];

increment_rooms(api);

function increment_rooms(url) {
  clear_list_rooms();
  fetch(url)
    .then((resp) => resp.json())
    .then((data) => {
      const length = data["data"].length;

      for (let i = 0; i < length; i++) {
        let room = template.content.cloneNode(true);

        let slider_imgs = room.querySelector(".slider");

        list_imgs.forEach((path) => {
          let new_img = document.createElement("img");
          new_img.src = path;
          slider_imgs.appendChild(new_img);
        });

        let type = room.querySelector(".type");

        type.textContent += " " + data["data"][i]["room_type"];

        let capacity = room.querySelector(".capacity");

        capacity.textContent += " " + data["data"][i]["capacity"];

        let description = room.querySelector(".description");

        description.textContent += " " + data["data"][i]["description"];

        let price = room.querySelector(".price");

        price.textContent += " " + data["data"][i]["price"];

        const slider = room.querySelector(".slider");
        const prevButton = room.querySelector(".prev-button");
        const nextButton = room.querySelector(".next-button");
        const slides = Array.from(slider.querySelectorAll("img"));
        const slideCount = slides.length;
        let slideIndex = 0;

        prevButton.addEventListener("click", showPreviousSlide);
        nextButton.addEventListener("click", showNextSlide);

        function showPreviousSlide() {
          slideIndex = (slideIndex - 1 + slideCount) % slideCount;
          updateSlider();
        }

        function showNextSlide() {
          slideIndex = (slideIndex + 1) % slideCount;
          updateSlider();
        }

        function updateSlider() {
          slides.forEach((slide, index) => {
            if (index === slideIndex) {
              slide.style.display = "block";
            } else {
              slide.style.display = "none";
            }
          });
        }

        updateSlider();

        list_rooms.appendChild(room);
      }
    });
}

function clear_list_rooms() {
  let rooms = list_rooms.children;
  while (rooms.length > 4) {
    for (let i = 0; i < rooms.length; i++) {
      if (rooms[i] instanceof HTMLLIElement) {
        list_rooms.removeChild(rooms[i]);
      }
    }
  }
}

function url_constructor() {
  params = "";
  if (filter_params["type"] != "") params += filter_params["type"] + "&";
  if (filter_params["prices"] != "") params += filter_params["prices"] + "&";
  if (filter_params["capacity"] != "")
    params += filter_params["capacity"] + "&";

  if (sort_params == "price_up") params += "sort=price&order=asc";
  if (sort_params == "price_down") params += "sort=price&order=desc";
  if (sort_params == "capacity_up") params += "sort=capacity&order=asc";
  if (sort_params == "capacity_down") params += "sort=capacity&order=desc";
  if (sort_params == "type_up") params += "sort=type&order=asc";
  if (sort_params == "type_down") params += "sort=type&order=desc";

  return api + "?" + params;
}

// Buttons clickabled

const sort = document.querySelector(".sort");
sort.addEventListener("click", () => {
  document.querySelector(".sort-menu").style.display = "flex";
});

const sort_menu = document.querySelector(".sort-menu");
const sort_price_up = sort_menu.querySelector(".sort-price__up");
sort_price_up.addEventListener("click", () => {
  sort_params = "price_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_price_down = sort_menu.querySelector(".sort-price__down");
sort_price_down.addEventListener("click", () => {
  sort_params = "price_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_capacity_up = sort_menu.querySelector(".sort-capacity__up");
sort_capacity_up.addEventListener("click", () => {
  sort_params = "capacity_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_capacity_down = sort_menu.querySelector(".sort-capacity__down");
sort_capacity_down.addEventListener("click", () => {
  sort_params = "capacity_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_type_up = sort_menu.querySelector(".sort-type__up");
sort_type_up.addEventListener("click", () => {
  sort_params = "type_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_type_down = sort_menu.querySelector(".sort-type__down");
sort_type_down.addEventListener("click", () => {
  sort_params = "type_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_reboot = sort_menu.querySelector(".sort-reboot");
sort_reboot.addEventListener("click", () => {
  sort_params = "";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});
