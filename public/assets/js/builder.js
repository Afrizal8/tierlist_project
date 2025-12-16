document.addEventListener("DOMContentLoaded", () => {
  const imageFilesInput = document.querySelector("#imageFiles");
  const addItemBtn = document.querySelector("#addItemBtn");
  const newItemName = document.querySelector("#newItemName");
  const itemPool = document.querySelector("#itemPool");
  const saveBtn = document.querySelector("#saveBtn");
  const titleInput = document.querySelector("#title");
  const descInput = document.querySelector("#description");
  const thumbnailSelect = document.querySelector("#thumbnailSelect");
  const hiddenItemsJson = document.querySelector("#items_json");
  const hiddenThumbnail = document.querySelector("#thumbnail_index");
  const tierForm = document.querySelector("#tierForm");

  let items = []; // semua item pool
  let fileObjects = []; // menyimpan File object dari upload
  let itemCounter = 0; // agar id unik tidak bentrok

  /* =============================
       CREATE ITEM CARD
    ============================== */
  function createItemCard(name, imgUrl, fileIndex) {
    const id = ++itemCounter;

    const div = document.createElement("div");
    div.classList.add("item-card");
    div.draggable = true;
    div.dataset.itemId = id;
    div.dataset.fileIndex = fileIndex;

    div.innerHTML = `
            <div class="item-imgwrap">
                ${
                  imgUrl
                    ? `<img class="thumb" src="${imgUrl}" />`
                    : `<div class="thumb placeholder">No Image</div>`
                }
            </div>
            <div class="item-name">${name}</div>
        `;

    addDragEvents(div);
    return div;
  }

  /* =============================
       DRAG & DROP HANDLERS
    ============================== */
  function addDragEvents(card) {
    card.addEventListener("dragstart", () => {
      card.classList.add("dragging");
    });

    card.addEventListener("dragend", () => {
      card.classList.remove("dragging");
    });
  }

  document.querySelectorAll(".tier-drop").forEach((drop) => {
    drop.addEventListener("dragover", (e) => {
      e.preventDefault();
      drop.classList.add("drag-over");
    });

    drop.addEventListener("dragleave", () => {
      drop.classList.remove("drag-over");
    });

    drop.addEventListener("drop", () => {
      drop.classList.remove("drag-over");
      const dragging = document.querySelector(".dragging");
      if (dragging) drop.appendChild(dragging);
    });
  });

  itemPool.addEventListener("dragover", (e) => {
    e.preventDefault();
    itemPool.classList.add("drag-over");
  });
  itemPool.addEventListener("dragleave", () => {
    itemPool.classList.remove("drag-over");
  });
  itemPool.addEventListener("drop", () => {
    itemPool.classList.remove("drag-over");
    const dragging = document.querySelector(".dragging");
    if (dragging) itemPool.appendChild(dragging);
  });

  /* =============================
       FILE UPLOAD HANDLING
    ============================== */
  let queuedFiles = []; // menyimpan files yang belum dipakai menjadi item

  imageFilesInput.addEventListener("change", (e) => {
    queuedFiles = Array.from(e.target.files);
    console.log("Queued files:", queuedFiles);
  });

  /* =============================
       ADD ITEM TO POOL
    ============================== */
  addItemBtn.addEventListener("click", () => {
    const name = newItemName.value.trim();
    if (!name) {
      alert("Nama item kosong!");
      return;
    }
    if (queuedFiles.length === 0) {
      alert("Anda belum memilih gambar untuk item!");
      return;
    }

    const file = queuedFiles.shift();
    const fileIndex = fileObjects.length;
    fileObjects.push(file);

    const imgUrl = URL.createObjectURL(file);
    const card = createItemCard(name, imgUrl, fileIndex);

    itemPool.appendChild(card);

    // Tambahkan ke thumbnail selector
    const opt = document.createElement("option");
    opt.value = fileIndex;
    opt.textContent = `${file.name}`;
    thumbnailSelect.appendChild(opt);

    newItemName.value = "";
  });

  /* =============================
       SAVE BUTTON
    ============================== */
  saveBtn.addEventListener("click", () => {
    const title = titleInput.value.trim();
    if (!title) {
      alert("Title masih kosong!");
      return;
    }

    // 1. kumpulkan semua posisi item
    const allCards = document.querySelectorAll(".item-card");

    const result = [];
    allCards.forEach((card) => {
      const id = card.dataset.itemId;
      const fileIndex = card.dataset.fileIndex;
      const name = card.querySelector(".item-name").textContent;
      let tier = null;

      let parent = card.parentElement;
      if (parent.classList.contains("tier-drop")) {
        tier = parent.dataset.tier;
      }

      result.push({
        id,
        name,
        tier,
        fileIndex,
      });
    });

    hiddenItemsJson.value = JSON.stringify(result);
    hiddenThumbnail.value = thumbnailSelect.value;
    tierForm.querySelector("input[name='title']").value = title;
    tierForm.querySelector("input[name='description']").value = descInput.value;

    // 2. masukkan file upload ke form
    fileObjects.forEach((file, i) => {
      const input = document.createElement("input");
      input.type = "file";
      input.name = "images[]";
      tierForm.appendChild(input);

      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
    });

    tierForm.submit();
  });
});
