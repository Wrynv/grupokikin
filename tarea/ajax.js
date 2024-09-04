document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("uploadForm");
    const statusDiv = document.getElementById("uploadStatus");
    const itemList = document.getElementById("item-list");

 
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(response => {
            statusDiv.innerHTML = response;
            loadItems();
        })
        .catch(error => {
            statusDiv.innerHTML = "Error al subir la imagen.";
            console.error('Error:', error);
        });
    });

    
    function loadItems() {
        fetch('index.php?action=getItems')
        .then(response => response.json())
        .then(data => {
            itemList.innerHTML = ''; 
            data.forEach(item => {
                itemList.innerHTML += `
                    <div>
                        <p>Nombre: ${item.nombre}</p>
                        <p>Precio: $${item.precio}</p>
                        <p>Fecha de Subida: ${item.fecha}</p>
                        <button onclick="comprarImagen('${item.nombre}', ${item.precio})">Comprar</button>
                    </div>
                `;
            });
        })
        .catch(error => console.error('Error:', error));
    }


    window.comprarImagen = function(nombre, precio) {
        const pago = prompt(`Vas a comprar ${nombre} por $${precio}. Ingresa el numero de tu tarjeta.`);
        if (pago) {
            alert("Compra realizada con éxito.");
        } else {
            alert("Error: No se ha completado la compra.");
        }
    }

    loadItems(); 
});



