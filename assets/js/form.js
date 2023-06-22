
let pictureFormContainer = document.querySelector('#picture-form-container')
let addPictureBtn = document.querySelector('#add-picture-btn')
let increment = 0;
if (addPictureBtn) {
    addPictureBtn.addEventListener('click', (event) => {
        let formTemplate = event.target.dataset.template
        insertToDom(formTemplate)
        increment++;
    })
}

function insertToDom(template) {
    let indexedFormTemplate = template.replace(
        /__name__/g,
        increment
    );
    const div = document.createElement('div');
    div.innerHTML = indexedFormTemplate
    pictureFormContainer.appendChild(div)
}
