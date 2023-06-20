import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["results"];

    fetchData(event) {
        let inputValue = event.target.value;
        inputValue === '' ? this.resultsTarget.classList.add('d-none') : this.resultsTarget.classList.remove('d-none');

        fetch('/search?keywords=' + inputValue)
            .then(response => response.json())
            .then(data => {
                this.resultsTarget.innerHTML = '';
                data.forEach(place => {
                    const li = document.createElement('li');
                    li.className = "dropdown-item";
                    li.textContent = `${place.name} / ${place.description}`;
                    this.resultsTarget.appendChild(li);
                    li.addEventListener('click', () => {
                        window.location.href = `/view/${place.slug}`;
                    })
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement du choix de sites:', error);
            });
    }
}
