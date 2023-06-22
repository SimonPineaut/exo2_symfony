import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["div", "btn"];
    static values = {
        increment: Number
    }

    addForm() {
        let template = this.btnTarget.dataset.template
        let indexedFormTemplate = template.replace(
            /__name__/g,
            this.incrementValue
        )
        const div = document.createElement('div');
        div.innerHTML = indexedFormTemplate
        this.divTarget.appendChild(div)
        this.incrementValue++
        console.log(this.incrementValue);
    }
}
