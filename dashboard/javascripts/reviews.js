const template_comment = document.querySelector(".comment-template");
const list_comments = document.querySelector(".list-comments");

function increment_comments() {
  fetch("/api/router.php/reviews")
    .then((resp) => resp.json())
    .then((data) => {
      clear_list_comments();

      for (let i = 0; i < data["data"].length; i++) {
        const comment = template_comment.content.cloneNode(true);

        const name = comment.querySelector(".name");
        const rating = comment.querySelector(".rating-result");
        const comment_text = comment.querySelector(".comment-text");
        const date_comment = comment.querySelector(".date");

        name.textContent = data["data"][i]["fio"];
        rating_to_html(rating, data["data"][i]["rating"]);
        comment_text.textContent = data["data"][i]["review"];
        date_to_html(
          date_comment,
          new Date(data["data"][i]["publication_date"]),
        );
        list_comments.appendChild(comment);
      }
    });
}

increment_comments();

function date_to_html(el, date) {
  el.textContent = `${date.getFullYear()}.${date.getMonth()}.${date.getDate()}`;
}

function rating_to_html(doc, rat) {
  if (rat == 5 || rat == 4 || rat == 3 || rat == 2 || rat == 1 || rat == 0) {
    for (let i = 0; i < rat; i++) {
      let span = document.createElement("span");
      span.classList.add("active");
      span.innerHTML = "★";
      doc.appendChild(span);
    }
  } else {
    let des_rat = rat;
    let dop_rat = rat * 10;

    while (des_rat > 1) {
      des_rat -= 1;
    }

    while (dop_rat > 10) {
      dop_rat -= 10;
    }

    for (let i = 0; i < rat - des_rat; i++) {
      let span = document.createElement("span");
      span.classList.add("active");
      span.innerHTML = "★";
      doc.appendChild(span);
    }

    let span = document.createElement("span");
    span.classList.add("active-" + dop_rat);
    span.innerHTML = "★";
    doc.appendChild(span);
  }

  for (let i = doc.children.length; i < 5; i++) {
    let span = document.createElement("span");
    span.innerHTML = "★";
    doc.appendChild(span);
  }

  let h2 = document.createElement("h2");
  h2.classList.add("tooltip-text");
  h2.innerText = "" + rat;
  doc.appendChild(h2);
}

function clear_list_comments() {
  let comments = list_comments.children;
  while (comments.length > 4) {
    for (let i = 0; i < comments.length; i++) {
      if (comments[i] instanceof HTMLLIElement) {
        list_comments.removeChild(comments[i]);
      }
    }
  }
}

const form_name = document.querySelector(".form-name");
const form_rating = document.querySelector(".rating-area");
const form_comment = document.querySelector(".form-comment");
const form_btn = document.querySelector(".form-public");
form_btn.addEventListener("click", () => {
  let rat = 1;
  for (let i = 0; i < form_rating.children.length; i++) {
    if (form_rating.children[i] instanceof HTMLInputElement) {
      if (form_rating.children[i].checked) rat = form_rating.children[i].value;
    }
  }

  let request = `/api/router.php/review?fio=${form_name.value}&review=${form_comment.value}&rating=${rat}`;

  fetch(request, { method: "post" }).then();

  increment_comments();
});
