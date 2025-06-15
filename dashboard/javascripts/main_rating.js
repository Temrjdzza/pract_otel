let rat = 3.5;

let doc = document.getElementById("rating-result");

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
