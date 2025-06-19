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

        let img_arr = data["data"][i]["images"];

        for (let j = 0; j < img_arr.length; j++) {
          let new_img = document.createElement("img");
          new_img.src = img_arr[j];
          slider_imgs.appendChild(new_img);
        }

        if (img_arr.length == 0) {
          let new_img = document.createElement("img");
          new_img.src = "/images/default.jpg";
          slider_imgs.appendChild(new_img);
        }

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

        if (img_arr.length <= 1) {
          prevButton.style.display = "none";
          nextButton.style.display = "none";
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
  if (filter_params["type"] != "")
    params += "room_type=" + filter_params["type"] + "&";
  if (filter_params["prices"] != "")
    params += "price=" + filter_params["prices"] + "&";
  if (filter_params["capacity"] != "")
    params += "capacity=" + filter_params["capacity"] + "&";

  if (sort_params == "price_up") params += "sort=price&order=asc";
  if (sort_params == "price_down") params += "sort=price&order=desc";
  if (sort_params == "capacity_up") params += "sort=capacity&order=asc";
  if (sort_params == "capacity_down") params += "sort=capacity&order=desc";
  if (sort_params == "type_up") params += "sort=type&order=asc";
  if (sort_params == "type_down") params += "sort=type&order=desc";

  return api + "?" + params;
}

// Buttons clickabled
// sort menu
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

// filter menu
const filter_menu = document.querySelector(".filter-menu");

const filter = document.querySelector(".filter");
filter.addEventListener("click", () => {
  filter_menu.style.display = "flex";
});

const filter_combobox = filter_menu.querySelector(".combobox");
const filter_price_min = filter_menu.querySelector(".price-min");
const filter_price_max = filter_menu.querySelector(".price-max");
const filter_capacity = filter_menu.querySelector(".capacity-count");

const filter_reboot = filter_menu.querySelector(".reboot");
filter_reboot.addEventListener("click", () => {
  filter_params = {
    type: "",
    prices: "",
    capacity: "",
  };
  increment_rooms(url_constructor());
  filter_menu.style.display = "none";
});

const filter_find = filter_menu.querySelector(".find");
filter_find.addEventListener("click", () => {
  if (filter_combobox.value == "all") filter_params["type"] = "";
  else filter_params["type"] = filter_combobox.value;

  if (filter_price_min.value != "" && filter_price_max.value != "")
    filter_params["prices"] =
      filter_price_min.value + "-" + filter_price_max.value;
  else filter_params["prices"] = "";

  if (filter_capacity.value != "")
    filter_params["capacity"] = filter_capacity.value;
  else filter_params["capacity"] = "";

  increment_rooms(url_constructor());
  filter_menu.style.display = "none";
});
