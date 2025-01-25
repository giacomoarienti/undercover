document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.color-checkbox');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const quantityInput = document.getElementById(`quantity_${checkbox.id.split('_')[1]}`);
            if (checkbox.checked) {
                quantityInput.removeAttribute('disabled');
            } else {
                quantityInput.setAttribute('disabled', 'true');
                quantityInput.value = '';
            }
        });
    });
});

function nextImage() {
    return document.getElementById('image-preview-container').children.length - 1;
}

function addImage() {
    let number = nextImage();
    let newImage = document.createElement('div');
    newImage.classList.add('d-flex', 'flex-column', 'justify-content-between','align-items-center', 'm-2', 'col-2', 'flex-shrink-0');

    let input = document.createElement('input');
    input.id = `image_${number}`;
    input.type = 'file';
    input.accept = 'image/*';
    input.name = 'images[]';
    input.hidden = true;

    newImage.appendChild(input);
    input.addEventListener('cancel', function () {
        newImage.remove();
    });
    input.onchange = function () {
        if (this.files && this.files[0]) {
            let img_preview = document.createElement('img');
            img_preview.src = URL.createObjectURL(this.files[0]);
            img_preview.alt = '';
            img_preview.classList.add('img-thumbnail', 'm-2', 'h-100');
            newImage.appendChild(img_preview);

            let button = document.createElement('button');
            button.classList.add('btn', 'btn-danger', 'm-2', 'mt-0');
            button.type = 'button';
            button.onclick = function () {
                newImage.remove();
            };
            button.innerHTML = 'Delete\n<span aria-hidden="true" class="fa-solid fa-circle-minus"></i>';
            newImage.appendChild(button);
        }else{
            newImage.remove();
        }
    }
    input.click();
    document.getElementById('image-add').before(newImage);
}

function addDeleteImage(id) {
    document.getElementById(`existing-image-${id}`).remove();

    let deleteCommand = document.createElement('input');
    deleteCommand.type = 'hidden';
    deleteCommand.name = 'delete_images[]';
    deleteCommand.value = id;

    document.getElementById(`form`).appendChild(deleteCommand);
}
