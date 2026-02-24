
const add = document.getElementById("add");

add.addEventListener("click", (e)=>{
    e.stopPropagation();
    e.preventDefault();

    const tbody = document.getElementById("taulu");
    const node = document.getElementById("kopio");
    const clone = node.cloneNode(true);
    
    tbody.appendChild(clone);
    const adde = clone.lastElementChild;
    adde.firstElementChild.innerHTML = "-";
   
    adde.lastElementChild.addEventListener("click", (e) => {
        e.stopPropagation();
        e.preventDefault();
        clone.remove();
    });

});

